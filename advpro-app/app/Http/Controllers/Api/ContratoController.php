<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Cliente;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ContratoController extends Controller
{
    public function generarPdf(Contrato $contrato)
    {
        $contrato->load(['cliente', 'proyecto']);
        $pdf = PDF::loadView('contratos.contrato_pdf', compact('contrato'));
        return $pdf->stream('contrato-'.$contrato->serial.'.pdf');
    }

    public function index(Request $request)
    {
        $contratos = Contrato::with(['cliente', 'proyecto'])->paginate(10);
        $clientes = Cliente::all();
        $proyectos = Proyecto::all();

        $proyectosJson = json_encode($proyectos->map(function ($proyecto) {
            return [
                'id' => $proyecto->id,
                'nombre' => $proyecto->nombre,
                'presupuesto' => (float) $proyecto->presupuesto,
                'estado' => $proyecto->estado,
            ];
        }));

        $clientsJson = json_encode($clientes->map(function ($cliente) {
            // Se asume que 'Activo' y 'Pendiente' son los estados que impiden un nuevo contrato.
            $activeContractsCount = $cliente->contratos()->whereIn('estado', ['Activo', 'Pendiente'])->count();
            return [
                'id' => $cliente->id,
                'nombre' => $cliente->nombre,
                'active_contracts_count' => $activeContractsCount,
            ];
        }));

        return $request->wantsJson()
            ? response()->json($contratos, 200)
            : view('contratos.panel', compact('contratos', 'clientes', 'proyectos', 'proyectosJson', 'clientsJson'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $proyectos = Proyecto::all();

        $proyectosJson = json_encode($proyectos->map(function ($proyecto) {
            return [
                'id' => $proyecto->id,
                'nombre' => $proyecto->nombre,
                'presupuesto' => (float) $proyecto->presupuesto,
                'estado' => $proyecto->estado,
            ];
        }));

        $clientsJson = json_encode($clientes->map(function ($cliente) {
            // Se asume que 'Activo' y 'Pendiente' son los estados que impiden un nuevo contrato.
            $activeContractsCount = $cliente->contratos()->whereIn('estado', ['Activo', 'Pendiente'])->count();
            return [
                'id' => $cliente->id,
                'nombre' => $cliente->nombre,
                'active_contracts_count' => $activeContractsCount,
            ];
        }));

        return view('contratos.create', compact('clientes', 'proyectos', 'proyectosJson', 'clientsJson'));
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

        // INICIO DE VALIDACIÓN CLAVE: Un cliente no puede tener más de 1 contrato en Activo o Pendiente
        $existingActiveContracts = Contrato::where('id_cliente', $validated['id_cliente'])
                                           ->whereIn('estado', ['Activo', 'Pendiente']) // <--- CAMBIO AQUÍ
                                           ->count();

        Log::info('STORE - Cliente ID: ' . $validated['id_cliente']);
        Log::info('STORE - Contratos Activo/Pendiente encontrados: ' . $existingActiveContracts);

        if ($existingActiveContracts > 0) {
            return redirect()->back()->withErrors(['id_cliente' => 'El cliente ya tiene un contrato en estado "Activo" o "Pendiente". Solo puede tener uno a la vez.'])->withInput();
        }
        // FIN DE VALIDACIÓN CLAVE

        // Validación del estado del proyecto en el servidor (existente)
        $proyecto = Proyecto::find($validated['id_proyecto']);
        // Aquí se mantiene la lógica original si los estados de proyecto son 'en espera' o 'en proceso'
        if ($proyecto && in_array($proyecto->estado, ['en espera', 'en proceso'])) {
            return redirect()->back()->withErrors(['id_proyecto' => 'No se puede crear un contrato para proyectos en estado "en espera" o "en proceso".'])->withInput();
        }

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

        $proyectosJson = json_encode($proyectos->map(function ($proyecto) {
            return [
                'id' => $proyecto->id,
                'nombre' => $proyecto->nombre,
                'presupuesto' => (float) $proyecto->presupuesto,
                'estado' => $proyecto->estado,
            ];
        }));

        $clientsJson = json_encode($clientes->map(function ($cliente) use ($contrato) {
            // Se asume que 'Activo' y 'Pendiente' son los estados que impiden un nuevo contrato.
            // Excluir el contrato que se está editando.
            $activeContractsCount = $cliente->contratos()
                                           ->where('id', '!=', $contrato->id)
                                           ->whereIn('estado', ['Activo', 'Pendiente']) // <--- CAMBIO AQUÍ
                                           ->count();
            return [
                'id' => $cliente->id,
                'nombre' => $cliente->nombre,
                'active_contracts_count' => $activeContractsCount,
            ];
        }));

        return view('contratos.edit', compact('contrato', 'clientes', 'proyectos', 'proyectosJson', 'clientsJson'));
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

        // INICIO DE VALIDACIÓN CLAVE: Un cliente no puede tener más de 1 contrato en Activo o Pendiente (excluyendo el actual)
        $existingActiveContracts = Contrato::where('id_cliente', $validated['id_cliente'])
                                           ->where('id', '!=', $contrato->id)
                                           ->whereIn('estado', ['Activo', 'Pendiente']) // <--- CAMBIO AQUÍ
                                           ->count();

        Log::info('UPDATE - Cliente ID: ' . $validated['id_cliente']);
        Log::info('UPDATE - Contrato ID a excluir: ' . $contrato->id);
        Log::info('UPDATE - Contratos Activo/Pendiente encontrados: ' . $existingActiveContracts);

        if ($existingActiveContracts > 0) {
            return redirect()->back()->withErrors(['id_cliente' => 'El cliente ya tiene otro contrato en estado "Activo" o "Pendiente". Solo puede tener uno a la vez.'])->withInput();
        }
        // FIN DE VALIDACIÓN CLAVE

        // Validación del estado del proyecto en el servidor (existente)
        $proyecto = Proyecto::find($validated['id_proyecto']);
        // Aquí se mantiene la lógica original si los estados de proyecto son 'en espera' o 'en proceso'
        if ($proyecto && in_array($proyecto->estado, ['en espera', 'en proceso'])) {
            return redirect()->back()->withErrors(['id_proyecto' => 'No se puede actualizar un contrato para proyectos en estado "en espera" o "en proceso".'])->withInput();
        }

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