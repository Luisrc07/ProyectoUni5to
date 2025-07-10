<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Staff;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;

class EquipoController extends Controller
{
    /**
     * Muestra una lista de todos los equipos y maneja el filtrado.
     */
    public function index(Request $request)
    {
        $query = Equipo::with('responsableStaff');

        if ($request->filled('estado') && in_array($request->estado, ['Nuevo', 'Usado', 'Reparado'])) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('tipo_equipo')) {
            $query->where('tipo_equipo', $request->tipo_equipo);
        }

        if ($request->filled('responsable')) {
            $query->where('responsable_id', $request->responsable);
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

        $equipos = $query->paginate(10)->appends($request->query());

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
        $query = Equipo::with('responsableStaff');

        if ($request->filled('estado') && in_array($request->estado, ['Nuevo', 'Usado', 'Reparado'])) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('tipo_equipo')) {
            $query->where('tipo_equipo', $request->tipo_equipo);
        }

        if ($request->filled('responsable')) {
            $query->where('responsable_id', $request->responsable);
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

        $equipos_para_reporte = $query->get();

        $pdf = Pdf::loadView('equipos.reporte_pdf', compact('equipos_para_reporte'));
                
        // Cambiado de download() a stream() para mostrar el PDF en el navegador

        return $pdf->stream('reporte_equipos_' . date('Y-m-d') . '.pdf');
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
            'descripcion' => 'nullable|string|max:500',
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