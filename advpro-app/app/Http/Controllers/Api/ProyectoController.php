<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Staff;
use App\Models\Equipo;
use App\Models\ProyectoRecurso;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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

        // Filtro por Duración Estimada en Minutos (Nuevo)
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

        $proyectos = $query->paginate(7)->withQueryString();

        // Guardar los valores de los filtros actuales para que el formulario los recuerde
        $filter_values = $request->query();

        // Devolver la respuesta en JSON si es una petición API, o la vista si es web
        return $request->wantsJson()
            ? response()->json(['proyectos' => $proyectos, 'personal' => $personal, 'equipos' => $equipos], 200)
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
     * La lógica de fechas para los recursos se calcula aquí.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'duracion_estimada_minutos' => 'required|integer|min:1', // Nuevo campo
            'presupuesto' => 'nullable|numeric|min:0',
            'estado' => 'required|string|in:En espera,En proceso,Realizado',
            'lugar' => 'nullable|string|max:255',
            'responsable_id' => 'nullable|exists:staff,id',

            // Validaciones para recursos de personal
            'recursos_personal' => 'nullable|array',
            'recursos_personal.*.staff_id' => 'required_with:recursos_personal|exists:staff,id',

            // Validaciones para recursos de equipos
            'recursos_equipos' => 'nullable|array',
            'recursos_equipos.*.equipo_id' => 'required_with:recursos_equipos|exists:equipos,id',
            'recursos_equipos.*.cantidad' => 'nullable|integer|min:1',
        ]);

        // Crear el proyecto
        $proyecto = Proyecto::create($validatedData);

        // Guardar personal asignado usando la relación polimórfica
        if (isset($validatedData['recursos_personal'])) {
            foreach ($validatedData['recursos_personal'] as $recursoPersonal) {
                ProyectoRecurso::create([
                    'proyecto_id' => $proyecto->id,
                    'asignable_id' => $recursoPersonal['staff_id'],
                    'asignable_type' => Staff::class,
                    'cantidad' => null, // Asegura que 'cantidad' sea null para personal
                    // Las fechas de asignación de personal se derivan del proyecto
                    'fecha_asignacion' => Carbon::now(),
                    'fecha_fin_asignacion' => Carbon::now()->addMinutes($proyecto->duracion_estimada_minutos),
                ]);
            }
        }

        // Guardar equipos asignados usando la relación polimórfica
        if (isset($validatedData['recursos_equipos'])) {
            foreach ($validatedData['recursos_equipos'] as $recursoEquipo) {
                ProyectoRecurso::create([
                    'proyecto_id' => $proyecto->id,
                    'asignable_id' => $recursoEquipo['equipo_id'],
                    'asignable_type' => Equipo::class,
                    'cantidad' => $recursoEquipo['cantidad'] ?? null,
                    // Las fechas de asignación de equipo se derivan del proyecto
                    'fecha_asignacion' => Carbon::now(),
                    'fecha_fin_asignacion' => Carbon::now()->addMinutes($proyecto->duracion_estimada_minutos),
                ]);
            }
        }

        return $request->wantsJson()
            ? response()->json(['message' => 'Proyecto y recursos creados', 'data' => $proyecto->load(['personalAsignado', 'equiposAsignados'])], 201)
            : redirect()->route('proyectos.index')->with('alert', [
                'type' => 'success',
                'title' => '¡Éxito!',
                'message' => 'Proyecto y recursos creados correctamente.',
                'button' => 'Aceptar'
            ]);
    }

    /**
     * Muestra los detalles de un proyecto específico, incluyendo sus recursos.
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
     */
    public function edit(Proyecto $proyecto, Request $request)
    {
        $personal = Staff::all();
        $equipos = Equipo::all(); // Cargar todos los equipos

        // Cargar las relaciones de recursos para prellenar el formulario
        $proyecto->load(['personalAsignado', 'equiposAsignados']);

        return $request->wantsJson()
            ? response()->json($proyecto, 200)
            : view('proyectos.edit', compact('proyecto', 'personal', 'equipos'));
    }

    /**
     * Actualiza un proyecto existente y sus recursos asociados en la base de datos.
     * La lógica de fechas para los recursos se calcula aquí.
     */
    public function update(Request $request, Proyecto $proyecto)
    {
        $validatedData = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'duracion_estimada_minutos' => 'sometimes|integer|min:1', // Nuevo campo
            'presupuesto' => 'nullable|numeric|min:0',
            'estado' => 'sometimes|string|in:En espera,En proceso,Realizado',
            'lugar' => 'nullable|string|max:255',
            'responsable_id' => 'nullable|exists:staff,id',

            // Validaciones para recursos de personal
            'recursos_personal' => 'nullable|array',
            'recursos_personal.*.id' => 'nullable|exists:proyecto_recursos,id', // Para actualizar existentes
            'recursos_personal.*.staff_id' => 'required_with:recursos_personal|exists:staff,id',

            // Validaciones para recursos de equipos
            'recursos_equipos' => 'nullable|array',
            'recursos_equipos.*.id' => 'nullable|exists:proyecto_recursos,id', // Para actualizar existentes
            'recursos_equipos.*.equipo_id' => 'required_with:recursos_equipos|exists:equipos,id',
            'recursos_equipos.*.cantidad' => 'nullable|integer|min:1',
        ]);

        $proyecto->update($validatedData);

        // Sincronizar o actualizar recursos
        // Primero, elimina los recursos que ya no están presentes en la solicitud
        $currentResourceIds = $proyecto->recursos->pluck('id')->toArray();
        $newPersonalResourceIds = collect($validatedData['recursos_personal'] ?? [])->pluck('id')->filter()->toArray();
        $newEquipoResourceIds = collect($validatedData['recursos_equipos'] ?? [])->pluck('id')->filter()->toArray();

        $resourcesToDelete = array_diff($currentResourceIds, array_merge($newPersonalResourceIds, $newEquipoResourceIds));
        ProyectoRecurso::whereIn('id', $resourcesToDelete)->delete();

        // Actualizar o crear personal asignado
        if (isset($validatedData['recursos_personal'])) {
            foreach ($validatedData['recursos_personal'] as $recursoPersonal) {
                ProyectoRecurso::updateOrCreate(
                    ['id' => $recursoPersonal['id'] ?? null, 'proyecto_id' => $proyecto->id],
                    [
                        'asignable_id' => $recursoPersonal['staff_id'],
                        'asignable_type' => Staff::class,
                        'cantidad' => null, // Asegura que 'cantidad' sea null para personal
                        // Las fechas de asignación de personal se derivan del proyecto
                        'fecha_asignacion' => Carbon::now(),
                        'fecha_fin_asignacion' => Carbon::now()->addMinutes($proyecto->duracion_estimada_minutos),
                    ]
                );
            }
        }

        // Actualizar o crear equipos asignados
        if (isset($validatedData['recursos_equipos'])) {
            foreach ($validatedData['recursos_equipos'] as $recursoEquipo) {
                ProyectoRecurso::updateOrCreate(
                    ['id' => $recursoEquipo['id'] ?? null, 'proyecto_id' => $proyecto->id],
                    [
                        'asignable_id' => $recursoEquipo['equipo_id'],
                        'asignable_type' => Equipo::class,
                        'cantidad' => $recursoEquipo['cantidad'] ?? null,
                        // Las fechas de asignación de equipo se derivan del proyecto
                        'fecha_asignacion' => Carbon::now(),
                        'fecha_fin_asignacion' => Carbon::now()->addMinutes($proyecto->duracion_estimada_minutos),
                    ]
                );
            }
        }

        return $request->wantsJson()
            ? response()->json(['message' => 'Proyecto y recursos actualizados', 'data' => $proyecto->load(['personalAsignado', 'equiposAsignados'])], 200)
            : redirect()->route('proyectos.index')->with('alert', [
                'type' => 'success',
                'title' => '¡Éxito!',
                'message' => 'Proyecto y recursos actualizados correctamente.',
                'button' => 'Aceptar'
            ]);
    }

    /**
     * Elimina un proyecto de la base de datos (y sus recursos asociados por 'cascade').
     */
    public function destroy(Proyecto $proyecto, Request $request)
    {
        $proyecto->delete(); // La eliminación en cascada en la migración de proyecto_recursos se encargará de los recursos

        return $request->wantsJson()
            ? response()->json(['message' => 'Proyecto eliminado'], 204)
            : redirect()->route('proyectos.index')->with('alert', [
                'type' => 'success',
                'title' => '¡Éxito!',
                'message' => 'Proyecto eliminado correctamente.',
                'button' => 'Aceptar'
            ]);
    }

    /**
     * Exporta los proyectos filtrados a un archivo PDF usando Dompdf.
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