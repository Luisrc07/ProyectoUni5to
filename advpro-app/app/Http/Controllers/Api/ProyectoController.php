<?php

namespace App\Http\Controllers\Api; // Asegúrate de que el namespace sea correcto

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use App\Models\Staff; // Importa el modelo Staff
use App\Models\Equipo; // Importa el modelo Equipo
use App\Models\ProyectoRecurso; // Importa el modelo ProyectoRecurso
use Illuminate\Http\Request;
// use Carbon\Carbon; // Ya no es necesario si no se trabajan con fechas --> ¡Mantener comentado o eliminar!
use Barryvdh\DomPDF\Facade\Pdf; // Importa la fachada de DomPDF
use Illuminate\Support\Facades\DB; // Para transacciones
use App\Http\Requests\StoreProyectoRequest; // Importa el Form Request
use App\Http\Requests\UpdateProyectoRequest; // Importa el Form Request

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
                // Asegúrate de que 'staff.id' es la columna correcta en tu tabla de staff
                $q->where('staff.id', $request->input('personal_asignado_id'));
            });
        }

        // Filtro por equipo asignado (si necesitas filtrar proyectos que tienen cierto equipo)
        if ($request->filled('equipo_asignado_id')) {
            $query->whereHas('equiposAsignados', function ($q) use ($request) {
                // Asegúrate de que 'equipos.id' es la columna correcta en tu tabla de equipos
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

        // Paginación
        $proyectos = $query->paginate(7)->withQueryString();

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

            // Guardar personal asignado
            if (!empty($validatedData['recursos_personal'])) {
                foreach ($validatedData['recursos_personal'] as $recursoPersonal) {
                    ProyectoRecurso::create([
                        'proyecto_id' => $proyecto->id,
                        'asignable_id' => $recursoPersonal['staff_id'],
                        'asignable_type' => Staff::class,
                        'cantidad' => null, // 'cantidad' es null para personal
                    ]);
                }
            }

            // Guardar equipos asignados
            if (!empty($validatedData['recursos_equipos'])) {
                foreach ($validatedData['recursos_equipos'] as $recursoEquipo) {
                    ProyectoRecurso::create([
                        'proyecto_id' => $proyecto->id,
                        'asignable_id' => $recursoEquipo['equipo_id'],
                        'asignable_type' => Equipo::class,
                        'cantidad' => $recursoEquipo['cantidad'], // La validación asegura que exista y sea válida
                    ]);
                }
            }

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
        $proyecto->load(['personalAsignado', 'equiposAsignados']);

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
            // Actualizar los datos del proyecto
            $proyecto->update($validatedData);

            // --- Estrategia de Actualización de Recursos: Eliminar y Recrear (más simple para polimórficos) ---
            // Eliminar todas las asignaciones de recursos existentes para este proyecto
            // Esta relación asume que tienes un `hasMany` en tu modelo Proyecto a ProyectoRecurso
            // Si el nombre de tu relación es diferente, ajústalo.
            // Por ejemplo, si se llama 'recursosAsignados', sería $proyecto->recursosAsignados()->delete();
            $proyecto->recursosProyectoRecurso()->delete(); // Confirma el nombre de esta relación en tu modelo Proyecto.

            // Recrear asignaciones de personal
            if (!empty($validatedData['recursos_personal'])) {
                foreach ($validatedData['recursos_personal'] as $recursoPersonal) {
                    ProyectoRecurso::create([
                        'proyecto_id' => $proyecto->id,
                        'asignable_id' => $recursoPersonal['staff_id'],
                        'asignable_type' => Staff::class,
                        'cantidad' => null,
                    ]);
                }
            }

            // Recrear asignaciones de equipos
            if (!empty($validatedData['recursos_equipos'])) {
                foreach ($validatedData['recursos_equipos'] as $recursoEquipo) {
                    ProyectoRecurso::create([
                        'proyecto_id' => $proyecto->id,
                        'asignable_id' => $recursoEquipo['equipo_id'],
                        'asignable_type' => Equipo::class,
                        'cantidad' => $recursoEquipo['cantidad'],
                    ]);
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
        try {
            $proyecto->delete(); // Esto debería disparar la eliminación en cascada si está configurada en la migración

            return $request->wantsJson()
                ? response()->json(['message' => 'Proyecto eliminado correctamente.'], 204)
                : redirect()->route('proyectos.index')->with('alert', [
                    'type' => 'success',
                    'title' => '¡Éxito!',
                    'message' => 'Proyecto eliminado correctamente.',
                    'button' => 'Aceptar'
                ]);
        } catch (\Exception $e) {
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