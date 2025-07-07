<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Staff; // Asegúrate de que Staff esté importado
use Barryvdh\DomPDF\Facade\Pdf;

class EquipoController extends Controller
{
    /**
     * Muestra una lista de todos los equipos y maneja el filtrado.
     */
    public function index(Request $request)
    {
        // 1. Iniciar la consulta, cargando la relación 'responsableStaff'
        $query = Equipo::with('responsableStaff');

        // 2. Aplicar filtros si existen en la request
        // Filtrar por estado
        if ($request->filled('estado') && in_array($request->estado, ['Nuevo', 'Usado', 'Reparado'])) {
            $query->where('estado', $request->estado);
        }
        
        // Filtrar por tipo de equipo
        if ($request->filled('tipo_equipo')) {
            $query->where('tipo_equipo', $request->tipo_equipo);
        }

        // --- FILTRO POR RESPONSABLE ---
        if ($request->filled('responsable')) {
            $query->where('responsable', $request->responsable);
        }
        // -------------------------------------

        // Filtrar por fecha (creación)
        if ($request->filled('fecha_creacion')) {
            if ($request->fecha_creacion === 'nuevos') {
                $query->orderBy('created_at', 'desc'); // Ordenar por los más nuevos
            } elseif ($request->fecha_creacion === 'viejos') {
                $query->orderBy('created_at', 'asc'); // Ordenar por los más viejos
            }
        }

        // --- FILTROS POR VALOR ---
        if ($request->filled('valor_min')) {
            $query->where('valor', '>=', $request->valor_min);
        }

        if ($request->filled('valor_max')) {
            $query->where('valor', '<=', $request->valor_max);
        }
        // ---------------------------------

        // 3. Ejecutar la consulta y paginar los resultados
        $equipos = $query->paginate(5)->appends($request->query());
        
        // Obtener la lista de personal para el filtro
        $personal = Staff::all(['id', 'nombre']);

        return $request->wantsJson()
            ? response()->json($equipos, 200)
            : view('equipos.panel', compact('equipos', 'personal'));
    }

    /**
     * Genera un reporte PDF de los equipos filtrados.
     */
    public function generarReporte(Request $request)
    {
        // La lógica de filtrado es la misma que en el método index
        $query = Equipo::with('responsableStaff');

        if ($request->filled('estado') && in_array($request->estado, ['Nuevo', 'Usado', 'Reparado'])) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->filled('tipo_equipo')) {
            $query->where('tipo_equipo', $request->tipo_equipo);
        }

        if ($request->filled('responsable')) {
            $query->where('responsable', $request->responsable);
        }

        if ($request->filled('fecha_creacion')) {
            if ($request->fecha_creacion === 'nuevos') {
                $query->orderBy('created_at', 'desc');
            } elseif ($request->fecha_creacion === 'viejos') {
                $query->orderBy('created_at', 'asc');
            }
        }

        if ($request->filled('valor_min')) {
            $query->where('valor', '>=', $request->valor_min);
        }

        if ($request->filled('valor_max')) {
            $query->where('valor', '<=', $request->valor_max);
        }

        // Obtener todos los equipos que cumplen con los filtros (sin paginación)
        $equipos_para_reporte = $query->get();

        // Cargar la vista Blade que servirá como plantilla para el PDF
        $pdf = Pdf::loadView('equipos.reporte_pdf', compact('equipos_para_reporte'));
        
        // Descargar el PDF directamente.
        return $pdf->download('reporte_equipos_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Muestra el formulario para crear un nuevo equipo.
     */
    public function create()
    {
        $personal = Staff::all(['id', 'nombre']);
        return view('equipos.create', compact('personal'));
    }

    /**
     * Guarda un nuevo equipo en la base de datos.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'marca' => 'required|string|max:255',
            'tipo_equipo' => 'required|string|max:255',
            'estado' => 'required|string|in:Nuevo,Usado,Reparado',
            'ubicacion' => 'required|string|max:255',
            'responsable' => 'nullable|exists:staff,id',
            'cantidad' => 'required|integer|min:1|max:50',
            'valor' => 'required|numeric|min:0',
        ]);

        $equipo = Equipo::create($validatedData);

        return $request->wantsJson()
            ? response()->json(['message' => 'Equipo creado', 'data' => $equipo], 201)
            : redirect()->route('equipos.index')->with('alert', [
                'type' => 'success',
                'title' => '¡Éxito!',
                'message' => 'Equipo creado correctamente',
                'button' => 'Aceptar'
            ]);
    }

    /**
     * Muestra un equipo específico.
     */
    public function show(Equipo $equipo, Request $request)
    {
        $equipo->load('responsableStaff');

        return $request->wantsJson()
            ? response()->json($equipo, 200)
            : view('equipos.show', compact('equipo'));
    }

    /**
     * Muestra el formulario para editar un equipo.
     */
    public function edit(Equipo $equipo)
    {
        $equipo->load('responsableStaff');
        $personal = Staff::all(['id', 'nombre']);

        return view('equipos.edit', compact('equipo', 'personal'));
    }

    /**
     * Actualiza un equipo específico.
     */
    public function update(Request $request, Equipo $equipo)
    {
        $validatedData = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'marca' => 'sometimes|string|max:255',
            'tipo_equipo' => 'sometimes|string|max:255',
            'estado' => 'sometimes|string|in:Nuevo,Usado,Reparado',
            'ubicacion' => 'sometimes|string|max:255',
            'responsable' => 'nullable|exists:staff,id',
            'cantidad' => 'required|integer|min:1|max:50',
            'valor' => 'sometimes|numeric|min:0',
        ]);

        $equipo->update($validatedData);

        return $request->wantsJson()
            ? response()->json(['message' => 'Equipo actualizado', 'data' => $equipo], 200)
            : redirect()->route('equipos.index')->with('alert', [
                'type' => 'success',
                'title' => '¡Actualizado!',
                'message' => 'Equipo actualizado correctamente',
                'button' => 'Aceptar'
            ]);
    }

    /**
     * Elimina un equipo específico.
     */
    public function destroy(Equipo $equipo, Request $request)
    {
        $equipo->delete();

        return $request->wantsJson()
            ? response()->json(['message' => 'Equipo eliminado'], 204)
            : redirect()->route('equipos.index')->with('alert', [
                'type' => 'success',
                'title' => '¡Eliminado!',
                'message' => 'Equipo eliminado correctamente',
                'button' => 'Aceptar'
            ]);
    }
}
