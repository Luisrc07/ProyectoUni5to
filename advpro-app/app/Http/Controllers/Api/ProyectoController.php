<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use App\Models\Staff;
use App\Models\Equipo;
use App\Models\ProyectoRecurso;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProyectoRequest;
use App\Http\Requests\UpdateProyectoRequest;

class ProyectoController extends Controller
{
    /**
     * Aplica los filtros a la consulta de proyectos.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyFilters($query, Request $request)
    {
        // Filtro por Responsable
        if ($request->filled('responsable_id')) {
            $query->where('responsable_id', $request->input('responsable_id'));
        }

        // Filtro por Estado del proyecto
        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        // Filtro por rango de Presupuesto
        if ($request->filled('presupuesto_min')) {
            $query->where('presupuesto', '>=', $request->input('presupuesto_min'));
        }
        if ($request->filled('presupuesto_max')) {
            $query->where('presupuesto', '<=', $request->input('presupuesto_max'));
        }

        // Filtro por Duración Estimada en Minutos
        if ($request->filled('duracion_min')) {
            $query->where('duracion_estimada_minutos', '>=', $request->input('duracion_min'));
        }
        if ($request->filled('duracion_max')) {
            $query->where('duracion_estimada_minutos', '<=', $request->input('duracion_max'));
        }

        // Filtro por personal asignado (si necesitas filtrar proyectos que tienen cierto personal)
        if ($request->filled('personal_asignado_id')) {
            $query->whereHas('personalAsignado', function ($q) use ($request) {
                $q->where('staff.id', $request->input('personal_asignado_id'));
            });
        }

        // Filtro por equipo asignado (si necesitas filtrar proyectos que tienen cierto equipo)
        if ($request->filled('equipo_asignado_id')) {
            $query->whereHas('equiposAsignados', function ($q) use ($request) {
                $q->where('equipos.id', $request->input('equipo_asignado_id'));
            });
        }

        return $query;
    }

    /**
     * Muestra una lista de proyectos, con opciones de filtrado y paginación.
     * También carga personal y equipos para los selectores de filtro.
     */
    public function index(Request $request)
    {
        // Obtener todo el personal y equipos para los selectores de los formularios (crear y filtrar)
        $personal = Staff::all();
        $equipos = Equipo::all();

        // Iniciar la consulta Eloquent con las relaciones necesarias
        $query = Proyecto::with(['responsable', 'personalAsignado', 'equiposAsignados']);

        // Aplicar filtros
        $query = $this->applyFilters($query, $request);

        $proyectos = $query->paginate(5)->withQueryString();

        // Guardar los valores de los filtros actuales para que el formulario los recuerde
        $filter_values = $request->query();

        // Devolver la respuesta en JSON si es una petición API, o la vista si es web
        return $request->wantsJson()
            ? response()->json([
                'proyectos' => $proyectos,
                'personal' => $personal,
                'equipos' => $equipos
            ], 200)
            : view('proyectos.panel', compact('proyectos', 'personal', 'equipos', 'filter_values'));
    }

    /**
     * Muestra el formulario para crear un nuevo proyecto en una página separada.
     */
    public function create()
    {
        $personal = Staff::all();
        $equipos = Equipo::all();
        // Se pasa un proyecto nulo para que el formulario de creación sepa que no está en modo edición
        $proyecto = null;
        return view('proyectos.create_project_page', compact('personal', 'equipos', 'proyecto'));
    }

    /**
     * Almacena un nuevo proyecto y sus recursos asociados en la base de datos.
     *
     * @param  \App\Http\Requests\StoreProyectoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProyectoRequest $request)
    {
        // Los datos ya están validados por StoreProyectoRequest
        $validatedData = $request->validated();

        DB::beginTransaction(); // Iniciar transacción

        try {
            // Crear el proyecto
            $proyecto = Proyecto::create($validatedData);

            // --- Lógica para personal asignado ---
            // Obtener el ID del responsable del proyecto
            $responsableId = $validatedData['responsable_id'] ?? null;

            // Obtener todos los IDs de personal de la solicitud
            $requestedStaffIds = array_filter(array_column($validatedData['recursos_personal'] ?? [], 'staff_id'));

            // Si hay un responsable y no está ya en la lista de personal asignado, añadirlo
            if ($responsableId && !in_array($responsableId, $requestedStaffIds)) {
                $requestedStaffIds[] = $responsableId;
            }

            // Eliminar duplicados si los hubiera (aunque 'distinct' en el request ya ayuda)
            $requestedStaffIds = array_unique($requestedStaffIds);

            // Sincronizar personal asignado
            // El método sync eliminará las asignaciones antiguas que no estén en $requestedStaffIds
            // y añadirá las nuevas.
            $proyecto->personalAsignado()->sync($requestedStaffIds);

            // --- Lógica para equipos asignados y actualización de stock ---
            $equiposRecursos = $validatedData['recursos_equipos'] ?? [];
            $syncEquipoData = [];

            foreach ($equiposRecursos as $equipoData) {
                if (isset($equipoData['equipo_id']) && isset($equipoData['cantidad'])) {
                    $equipoId = $equipoData['equipo_id'];
                    $cantidad = $equipoData['cantidad'];
                    $syncEquipoData[$equipoId] = ['cantidad' => $cantidad];

                    // Decrementar el stock del equipo (solo para nuevas asignaciones en 'store')
                    $equipo = Equipo::find($equipoId);
                    if ($equipo) {
                        $equipo->decrement('stock', $cantidad);
                    }
                }
            }

            // Sincronizar equipos asignados
            $proyecto->equiposAsignados()->sync($syncEquipoData);

            DB::commit(); // Confirmar transacción

            return $request->wantsJson()
                ? response()->json([
                    'message' => 'Proyecto y recursos creados correctamente.',
                    'data' => $proyecto->load(['personalAsignado', 'equiposAsignados'])
                ], 201)
                : redirect()->route('proyectos.index')->with('alert', [
                    'type' => 'success',
                    'title' => '¡Éxito!',
                    'message' => 'Proyecto y recursos creados correctamente.',
                    'button' => 'Aceptar'
                ]);

        } catch (\Exception $e) {
            DB::rollBack(); // Revertir transacción en caso de error
            // Logear el error para depuración
            \Log::error("Error al crear proyecto y recursos: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());

            return $request->wantsJson()
                ? response()->json(['message' => 'Error al crear el proyecto y sus recursos.', 'error' => $e->getMessage()], 500)
                : back()->withInput()->with('alert', [
                    'type' => 'error',
                    'title' => '¡Error!',
                    'message' => 'Hubo un problema al crear el proyecto y sus recursos. ' . $e->getMessage(),
                    'button' => 'Aceptar'
                ]);
        }
    }

    /**
     * Muestra los detalles de un proyecto específico, incluyendo sus recursos.
     *
     * @param  \App\Models\Proyecto  $proyecto
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Proyecto $proyecto, Request $request)
    {
        // Cargar las relaciones de recursos
        $proyecto->load(['responsable', 'personalAsignado', 'equiposAsignados']);

        return $request->wantsJson()
            ? response()->json($proyecto, 200)
            : view('proyectos.show', compact('proyecto'));
    }

    /**
     * Muestra el formulario para editar un proyecto existente.
     *
     * @param  \App\Models\Proyecto  $proyecto
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Proyecto $proyecto, Request $request)
    {
        $personal = Staff::all();
        $equipos = Equipo::all();

        // Cargar las relaciones de recursos para prellenar el formulario
        $proyecto->load(['personalAsignado', 'equiposAsignados']);

        return $request->wantsJson()
            ? response()->json($proyecto, 200)
            : view('proyectos.edit', compact('proyecto', 'personal', 'equipos'));
    }

    /**
     * Actualiza un proyecto existente y sus recursos asociados en la base de datos.
     *
     * @param  \App\Http\Requests\UpdateProyectoRequest  $request
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProyectoRequest $request, Proyecto $proyecto)
    {
        // Los datos ya están validados por UpdateProyectoRequest
        $validatedData = $request->validated();

        DB::beginTransaction(); // Iniciar transacción

        try {
            // Guardar el estado actual de las asignaciones de equipo para calcular el cambio de stock
            $previousEquipmentAssignments = $proyecto->equiposAsignados->pluck('pivot.cantidad', 'id')->toArray();

            // Actualizar los datos del proyecto
            $proyecto->update($validatedData);

            // --- Lógica para personal asignado ---
            // Obtener el ID del responsable del proyecto
            $responsableId = $validatedData['responsable_id'] ?? null;

            // Obtener todos los IDs de personal de la solicitud
            $requestedStaffIds = array_filter(array_column($validatedData['recursos_personal'] ?? [], 'staff_id'));

            // Si hay un responsable y no está ya en la lista de personal asignado, añadirlo
            if ($responsableId && !in_array($responsableId, $requestedStaffIds)) {
                $requestedStaffIds[] = $responsableId;
            }

            // Eliminar duplicados si los hubiera (aunque 'distinct' en el request ya ayuda)
            $requestedStaffIds = array_unique($requestedStaffIds);

            // Sincronizar personal asignado
            $proyecto->personalAsignado()->sync($requestedStaffIds);

            // --- Lógica para equipos asignados y actualización de stock ---
            $equiposRecursos = $validatedData['recursos_equipos'] ?? [];
            $syncEquipoData = [];

            foreach ($equiposRecursos as $equipoData) {
                if (isset($equipoData['equipo_id']) && isset($equipoData['cantidad'])) {
                    $equipoId = $equipoData['equipo_id'];
                    $cantidad = $equipoData['cantidad'];
                    $syncEquipoData[$equipoId] = ['cantidad' => $cantidad];
                }
            }

            // Sincronizar equipos asignados
            $proyecto->equiposAsignados()->sync($syncEquipoData);

            // --- Ajustar el stock de equipos después de la sincronización ---
            $newAssignedEquipoIds = array_keys($syncEquipoData);

            // Incrementar stock para equipos que fueron removidos del proyecto
            foreach ($previousEquipmentAssignments as $equipoId => $oldQuantity) {
                if (!in_array($equipoId, $newAssignedEquipoIds)) {
                    $equipo = Equipo::find($equipoId);
                    if ($equipo) {
                        $equipo->increment('stock', $oldQuantity);
                    }
                }
            }

            // Ajustar stock para equipos que permanecen o son nuevos
            foreach ($syncEquipoData as $equipoId => $pivotData) {
                $newQuantity = $pivotData['cantidad'];
                $equipo = Equipo::find($equipoId);

                if ($equipo) {
                    $oldQuantity = $previousEquipmentAssignments[$equipoId] ?? 0; // 0 si es una nueva asignación

                    if ($newQuantity > $oldQuantity) {
                        // Si la cantidad aumentó, decrementar stock
                        $equipo->decrement('stock', $newQuantity - $oldQuantity);
                    } elseif ($newQuantity < $oldQuantity) {
                        // Si la cantidad disminuyó, incrementar stock
                        $equipo->increment('stock', $oldQuantity - $newQuantity);
                    }
                    // Si newQuantity == oldQuantity, no se necesita cambio de stock para este ítem
                }
            }

            DB::commit(); // Confirmar transacción

            return $request->wantsJson()
                ? response()->json([
                    'message' => 'Proyecto y recursos actualizados correctamente.',
                    'data' => $proyecto->load(['personalAsignado', 'equiposAsignados'])
                ], 200)
                : redirect()->route('proyectos.index')->with('alert', [
                    'type' => 'success',
                    'title' => '¡Éxito!',
                    'message' => 'Proyecto y recursos actualizados correctamente.',
                    'button' => 'Aceptar'
                ]);

        } catch (\Exception $e) {
            DB::rollBack(); // Revertir transacción
            \Log::error("Error al actualizar proyecto y recursos: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());

            return $request->wantsJson()
                ? response()->json(['message' => 'Error al actualizar el proyecto y sus recursos.', 'error' => $e->getMessage()], 500)
                : back()->withInput()->with('alert', [
                    'type' => 'error',
                    'title' => '¡Error!',
                    'message' => 'Hubo un problema al actualizar el proyecto y sus recursos. ' . $e->getMessage(),
                    'button' => 'Aceptar'
                ]);
        }
    }

    /**
     * Elimina un proyecto de la base de datos.
     * La eliminación de sus recursos asociados debe configurarse con 'cascade' en la migración de 'proyecto_recursos'.
     *
     * @param  \App\Models\Proyecto  $proyecto
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proyecto $proyecto, Request $request)
    {
        DB::beginTransaction(); // Iniciar transacción para asegurar la consistencia del stock

        try {
            // Antes de eliminar el proyecto, devolver el stock de los equipos asignados
            foreach ($proyecto->equiposAsignados as $equipoAsignado) {
                $equipo = Equipo::find($equipoAsignado->id);
                if ($equipo) {
                    $equipo->increment('stock', $equipoAsignado->pivot->cantidad);
                }
            }

            $proyecto->delete(); // Esto debería disparar la eliminación en cascada de ProyectoRecurso

            DB::commit(); // Confirmar transacción

            return $request->wantsJson()
                ? response()->json(['message' => 'Proyecto eliminado correctamente.'], 204)
                : redirect()->route('proyectos.index')->with('alert', [
                    'type' => 'success',
                    'title' => '¡Éxito!',
                    'message' => 'Proyecto eliminado correctamente.',
                    'button' => 'Aceptar'
                ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir transacción
            \Log::error("Error al eliminar proyecto: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());

            return $request->wantsJson()
                ? response()->json(['message' => 'Error al eliminar el proyecto.', 'error' => $e->getMessage()], 500)
                : back()->with('alert', [
                    'type' => 'error',
                    'title' => '¡Error!',
                    'message' => 'Hubo un problema al eliminar el proyecto. ' . $e->getMessage(),
                    'button' => 'Aceptar'
                ]);
        }
    }

    /**
     * Exporta los proyectos filtrados a un archivo PDF usando Dompdf.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportarPdf(Request $request)
    {
        // Iniciar la consulta Eloquent con las relaciones necesarias
        $query = Proyecto::with(['responsable', 'personalAsignado', 'equiposAsignados']);

        // Aplicar filtros usando la función privada
        $query = $this->applyFilters($query, $request);

        $proyectos = $query->get();

        // Cargar la vista específica para el PDF con los datos filtrados
        $pdf = Pdf::loadView('proyectos.pdf', compact('proyectos'));

        // Retornar el PDF para su descarga
        return $pdf->download('reporte_proyectos_filtrados.pdf');
    }
}
