<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Cliente;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContratoController extends Controller
{
    public function index(Request $request)
    {
        $contratos = Contrato::with(['cliente', 'proyecto'])->paginate(5);
        $clientes = Cliente::all();
        $proyectos = Proyecto::all();

        return $request->wantsJson()
            ? response()->json($contratos, 200)
            : view('contratos.panel', compact('contratos', 'clientes', 'proyectos'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $proyectos = Proyecto::all();

        return view('contratos.create', compact('clientes', 'proyectos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_cliente' => 'required|exists:clientes,id',
            'id_proyecto' => 'required|exists:proyectos,id',
            'fecha_contrato' => 'required|date',
            'estado' => 'required|string|in:activo,inactivo,finalizado,pendiente',
            'costo' => 'required|numeric|min:0',
        ]);

        if ($request->hasFile('documento')) {
            $validated['documento'] = $request->file('documento')->store('documentos');
        }

        $contrato = Contrato::create($validated);

        return $request->wantsJson()
            ? response()->json(['message' => 'Contrato creado', 'data' => $contrato], 201)
            : redirect()->route('contratos.index')->with('success', 'Contrato creado correctamente');
    }

    public function show(Contrato $contrato, Request $request)
    {
        $contrato->load(['cliente', 'proyecto']);

        return $request->wantsJson()
            ? response()->json($contrato, 200)
            : view('contratos.show', compact('contrato'));
    }

    public function edit(Contrato $contrato)
    {
        $clientes = Cliente::all();
        $proyectos = Proyecto::all();

        return view('contratos.edit', compact('contrato', 'clientes', 'proyectos'));
    }

    public function update(Request $request, Contrato $contrato)
    {
        $validated = $request->validate([
            'id_cliente' => 'sometimes|required|exists:clientes,id',
            'id_proyecto' => 'sometimes|required|exists:proyectos,id',
            'fecha_contrato' => 'sometimes|date', 
            'estado' => 'sometimes|string|in:activo,inactivo,finalizado,pendiente',
            'costo' => 'sometimes|required|numeric|min:0',
        ]);

        if ($request->hasFile('documento')) {
            if ($contrato->documento) {
                Storage::delete($contrato->documento);
            }
            $validated['documento'] = $request->file('documento')->store('documentos');
        }

        $contrato->update($validated);

        return $request->wantsJson()
            ? response()->json(['message' => 'Contrato actualizado', 'data' => $contrato], 200)
            : redirect()->route('contratos.index')->with('success', 'Contrato actualizado correctamente');
    }

    public function destroy(Contrato $contrato, Request $request)
    {
        if ($contrato->documento) {
            Storage::delete($contrato->documento);
        }

        $contrato->delete();

        return $request->wantsJson()
            ? response()->json(['message' => 'Contrato eliminado'], 204)
            : redirect()->route('contratos.index')->with('success', 'Contrato eliminado correctamente');
    }
}
