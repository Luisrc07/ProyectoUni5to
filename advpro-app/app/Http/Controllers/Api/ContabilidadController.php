<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asiento;
use App\Models\DetalleAsiento;
use App\Models\CuentaContable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContabilidadController extends Controller
{
    /**
     * Muestra una lista de todos los asientos y maneja el filtrado.
     */
    public function index(Request $request)
    {
        // 1. Iniciar la consulta para los asientos
        $queryAsientos = Asiento::with('detalles');

        // 2. Aplicar filtros si existen en la request (ejemplos, puedes expandirlos)
        // Filtrar por fecha (rango)
        if ($request->filled('fecha_inicio')) {
            $queryAsientos->where('fecha', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $queryAsientos->where('fecha', '<=', $request->fecha_fin);
        }

        // Filtrar por descripción (búsqueda parcial)
        if ($request->filled('descripcion')) {
            $queryAsientos->where('descripcion', 'like', '%' . $request->descripcion . '%');
        }

        // Ordenar por los más recientes por defecto
        $queryAsientos->latest();

        // 3. Ejecutar la consulta y paginar los resultados
        $asientos = $queryAsientos->paginate(5)->appends($request->query());

        // Cuentas que pueden recibir movimientos (hojas del árbol contable)
        $movimientoCuentas = CuentaContable::whereDoesntHave('hijas')->orderBy('codigo')->get();
        
        // Todas las cuentas (para seleccionar cuenta padre al crear una nueva cuenta)
        $todasCuentas = CuentaContable::orderBy('codigo')->get();

        return $request->wantsJson()
            ? response()->json($asientos, 200)
            : view('contabilidad.panel', compact('asientos', 'movimientoCuentas', 'todasCuentas'));
    }

    /**
     * Muestra el formulario para crear un nuevo asiento (si se usa fuera del modal del index).
     */
    public function create()
    {
        // Obtenemos solo las cuentas que pueden recibir movimientos (cuentas de detalle)
        $cuentas = CuentaContable::whereDoesntHave('hijas')->orderBy('codigo')->get();
        
        return view('contabilidad.asientos.create', compact('cuentas'));
    }

    /**
     * Guarda un nuevo asiento contable en la base de datos.
     */
    public function store(Request $request)
    {
        // ... (Tu validación se mantiene igual)

        // 👇 LA LÓGICA DE VALIDACIÓN DE PARTIDA DOBLE DEBE CAMBIAR AQUÍ
        $totalDebe = collect($request->detalles)->sum('debe');
        $totalHaber = collect($request->detalles)->sum('haber');

        if (bccomp($totalDebe, $totalHaber, 2) !== 0) {
            return redirect()->back()->withErrors(['balance' => 'El total del Debe ('. number_format($totalDebe, 2) .') debe ser igual al total del Haber ('. number_format($totalHaber, 2) .').'])->withInput();
        }

        try {
            DB::beginTransaction();

            $asiento = Asiento::create([
                'fecha' => $request->fecha,
                'descripcion' => $request->descripcion,
            ]);

            foreach ($request->detalles as $detalle) {
                // Solo guardar si hay una cuenta seleccionada
                if (!empty($detalle['id_cuenta'])) {
                    $asiento->detalles()->create([
                        'id_cuenta' => $detalle['id_cuenta'],
                        // Usa el operador '??' para asignar 0 si la clave no existe.
                        'debe' => $detalle['debe'] ?? 0,
                        'haber' => $detalle['haber'] ?? 0,
                        'descripcion_linea' => $detalle['descripcion_linea'] ?? null,
                    ]);
                }
            }

            DB::commit();
            
            return redirect()->route('contabilidad.index')->with('success', 'Asiento contable registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar el asiento contable: ' . $e->getMessage() . ' en la línea ' . $e->getLine());
            return redirect()->back()->withErrors(['error' => 'Error interno al guardar el asiento.'])->withInput();
        }
    }

    /**
     * Almacena una nueva Cuenta Contable.
     */
    public function storeCuenta(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:20|unique:cuenta_contable,codigo',
            'nombre' => 'required|string|max:100',
            'tipo' => 'required|in:activo,pasivo,patrimonio,ingreso,egreso,costo',
            'es_ajustable' => 'boolean',
            'cuenta_padre_id' => 'nullable|integer|exists:cuenta_contable,id_cuenta',
        ]);

        try {
            CuentaContable::create([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'tipo' => $request->tipo,
                'es_ajustable' => $request->boolean('es_ajustable'),
                'cuenta_padre_id' => $request->cuenta_padre_id,
            ]);

            return redirect()->back()->with('success', 'Cuenta contable creada exitosamente.');

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error al crear la cuenta contable: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Error al crear la cuenta: ' . $e->getMessage()])->withInput();
        }
    }
}
