<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Cliente;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf; // Importar la clase PDF
use Carbon\Carbon; 

class ContratoController extends Controller
{
    // ... (métodos index, create, store, show, edit, update, destroy existentes)

    /**
     * Genera un PDF para el contrato especificado.
     *
     * @param  \App\Models\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function generarPdf(Contrato $contrato)
    {
        // Cargar las relaciones necesarias
        $contrato->load(['cliente', 'proyecto']);

        // Pasar los datos a la vista del PDF
        $pdf = PDF::loadView('contratos.contrato_pdf', compact('contrato'));

        // Retornar el PDF para ser visualizado en el navegador
        return $pdf->stream('contrato-'.$contrato->serial.'.pdf');
    }

    public function index(Request $request)
    {
        // Asegurarse de que el serial esté disponible si se requiere para el panel.
        $contratos = Contrato::with(['cliente', 'proyecto'])->paginate(10);
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
        // AÑADIDO: Lógica de validación personalizada
        $validated = $request->validate([
            'id_cliente' => 'required|exists:clientes,id',
            'id_proyecto' => 'required|exists:proyectos,id',
            // VALIDACIÓN 1: La fecha no puede ser anterior a la constitución de la empresa
            'fecha_contrato' => 'required|date|after_or_equal:2018-01-01',
            // VALIDACIÓN 2: La fecha de entrega tiene un límite de 10 años
            'fecha_entrega' => [
                'sometimes',
                'date',
                'after_or_equal:fecha_contrato',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('fecha_contrato')) {
                        $fechaContrato = Carbon::parse($request->input('fecha_contrato'));
                        $fechaEntrega = Carbon::parse($value);
                        $maxFechaEntrega = $fechaContrato->copy()->addYears(10);

                        if ($fechaEntrega->gt($maxFechaEntrega)) {
                            $fail('La fecha de entrega no puede exceder los 10 años desde la fecha del contrato.');
                        }
                    }
                },
            ],
            'estado' => 'required|string|in:activo,inactivo,finalizado,pendiente',
            'costo' => 'required|numeric|min:0',
        ], [
            // AÑADIDO: Mensajes de error personalizados
            'fecha_contrato.after_or_equal' => 'La fecha del contrato no puede ser anterior a la constitución de la empresa (01/01/2018).',
            'fecha_entrega.after_or_equal' => 'La fecha de entrega no puede ser anterior a la fecha del contrato.',
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
        // AÑADIDO: Lógica de validación personalizada para la actualización
        $validated = $request->validate([
            'id_cliente' => 'sometimes|required|exists:clientes,id',
            'id_proyecto' => 'sometimes|required|exists:proyectos,id',
             // VALIDACIÓN 1: La fecha no puede ser anterior a la constitución de la empresa
            'fecha_contrato' => 'sometimes|date|after_or_equal:2018-01-01',
            // VALIDACIÓN 2: La fecha de entrega tiene un límite de 10 años
            'fecha_entrega' => [
                'sometimes',
                'date',
                'after_or_equal:fecha_contrato',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('fecha_contrato')) {
                        $fechaContrato = Carbon::parse($request->input('fecha_contrato'));
                        $fechaEntrega = Carbon::parse($value);
                        $maxFechaEntrega = $fechaContrato->copy()->addYears(10);

                        if ($fechaEntrega->gt($maxFechaEntrega)) {
                            $fail('La fecha de entrega no puede exceder los 10 años desde la fecha del contrato.');
                        }
                    }
                },
            ],
            'estado' => 'sometimes|string|in:activo,inactivo,finalizado,pendiente',
            'costo' => 'sometimes|required|numeric|min:0',
        ], [
            // AÑADIDO: Mensajes de error personalizados
            'fecha_contrato.after_or_equal' => 'La fecha del contrato no puede ser anterior a la constitución de la empresa (01/01/2018).',
            'fecha_entrega.after_or_equal' => 'La fecha de entrega no puede ser anterior a la fecha del contrato.',
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