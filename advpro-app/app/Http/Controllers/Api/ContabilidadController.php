<?php

namespace App\Http\Controllers\Api; // Asegúrate de que el namespace sea correcto si está en Api

use App\Http\Controllers\Controller;
use App\Models\Asiento;
use App\Models\DetalleAsiento;
use App\Models\CuentaContable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $asientos = $queryAsientos->paginate(10)->appends($request->query());

        // Cuentas que pueden recibir movimientos (hojas del árbol contable)
        $movimientoCuentas = CuentaContable::whereDoesntHave('hijas')->orderBy('codigo')->get();

        // Todas las cuentas (para seleccionar cuenta padre al crear una nueva cuenta)
        $todasCuentas = CuentaContable::orderBy('codigo')->get();

        // Verificar si ya existe un asiento de 'Constitucion'
        $asientoConstitucionExiste = Asiento::where('descripcion', 'Constitucion')->exists();


        return $request->wantsJson()
            ? response()->json($asientos, 200)
            : view('contabilidad.panel', compact('asientos', 'movimientoCuentas', 'todasCuentas', 'asientoConstitucionExiste'));
    }

    /**
     * Muestra el formulario para crear un nuevo asiento (si se usa fuera del modal del index).
     */
    public function create()
    {
        // Obtenemos solo las cuentas que pueden recibir movimientos (cuentas de detalle)
        $cuentas = CuentaContable::whereDoesntHave('hijas')->orderBy('codigo')->get();

        // Verificar si ya existe un asiento de 'Constitucion'
        $asientoConstitucionExiste = Asiento::where('descripcion', 'Constitucion')->exists();

        return view('contabilidad.asientos.create', compact('cuentas', 'asientoConstitucionExiste'));
    }

    /**
     * Guarda un nuevo asiento contable en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validación de balance
        $totalDebe = collect($request->detalles)->sum('debe');
        $totalHaber = collect($request->detalles)->sum('haber');

        if (bccomp($totalDebe, $totalHaber, 2) !== 0) {
            return redirect()->back()->withErrors(['balance' => 'El total del Debe ('. number_format($totalDebe, 2) .') debe ser igual al total del Haber ('. number_format($totalHaber, 2) .').'])->withInput();
        }

        // 2. Validación de asiento "Constitucion"
        $asientoConstitucionExiste = Asiento::where('descripcion', 'Constitucion')->exists();

        if (!$asientoConstitucionExiste) {
            // Si no existe, forzamos que la descripción sea 'Constitucion'
            $request->merge(['descripcion' => 'Constitucion']);
        } else {
            // Si ya existe, validamos que la descripción no sea 'Constitucion' a menos que sea el mismo asiento (esto es si permitieras editar)
            // Para el caso de creación, simplemente no permitimos crear otro con esa descripción si ya existe.
            if ($request->descripcion === 'Constitucion') {
                return redirect()->back()->withErrors(['descripcion' => 'Ya existe un asiento con la descripción "Constitucion". No se puede crear otro.'])->withInput();
            }
        }

        try {
            DB::beginTransaction();

            $asiento = Asiento::create([
                'fecha' => $request->fecha,
                'descripcion' => $request->descripcion,
            ]);

            foreach ($request->detalles as $detalle) {
                // Solo guardar si hay una cuenta seleccionada y al menos un valor en debe o haber
                if (!empty($detalle['id_cuenta']) && (isset($detalle['debe']) || isset($detalle['haber']))) {
                    $asiento->detalles()->create([
                        'id_cuenta' => $detalle['id_cuenta'],
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
            return redirect()->back()->withErrors(['error' => 'Error interno al guardar el asiento: ' . $e->getMessage()])->withInput();
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

    public function getCuentasPorTipo($tipo)
    {
        $cuentas = CuentaContable::where('tipo', $tipo)
            ->orderBy('codigo')
            ->get(['id_cuenta', 'codigo', 'nombre']); // Solo devuelve los datos necesarios

        return response()->json($cuentas);
    }

    /*
     * Calcula y devuelve el siguiente código contable disponible.
     */
    public function getSiguienteCodigo(Request $request)
    {
        $request->validate([
            'cuenta_padre_id' => 'nullable|integer|exists:cuenta_contable,id_cuenta',
            'tipo' => 'required|string' // Necesitamos el tipo para generar códigos de primer nivel
        ]);

        $cuentaPadreId = $request->input('cuenta_padre_id');

        if ($cuentaPadreId) {
            // --- Lógica para generar un sub-código ---
            $padre = CuentaContable::with('hijas')->find($cuentaPadreId);
            
            if ($padre->hijas->isEmpty()) {
                // Si el padre no tiene hijos, creamos el primer sub-código.
                // Esto puede variar según tu plan de cuentas (ej. '01' o '1').
                // Usaremos '1' para códigos más largos y '01' para más cortos.
                $sufijo = strlen($padre->codigo) > 4 ? '01' : '1';
                $nuevoCodigo = $padre->codigo . $sufijo;
            } else {
                // Si ya tiene hijos, encontramos el código más alto y le sumamos 1.
                $ultimoCodigo = $padre->hijas->max('codigo');
                $nuevoCodigo = (string)(((int)$ultimoCodigo) + 1);
            }

        } else {
            // --- Lógica para generar un código de primer nivel ---
            $ultimoCodigoPrincipal = CuentaContable::whereNull('cuenta_padre_id')
                ->where('tipo', $request->tipo)
                ->max('codigo');

            if ($ultimoCodigoPrincipal) {
                // Asumimos que los códigos de primer nivel van en miles (1000, 2000, etc.)
                $base = floor((int)$ultimoCodigoPrincipal / 1000) * 1000;
                $nuevoCodigo = (string)($base + 1000);
            } else {
                // Si no hay ninguno de ese tipo, asignamos uno por defecto.
                $mapaTipos = ['activo' => '1000', 'pasivo' => '2000', 'patrimonio' => '3000', 'ingreso' => '4000', 'egreso' => '5000', 'costo' => '6000'];
                $nuevoCodigo = $mapaTipos[$request->tipo] ?? '9000';
            }
        }

        return response()->json(['siguiente_codigo' => $nuevoCodigo]);
    }

    
    /**
     * Genera un mapeo de IDs de cuentas contables únicos a números de referencia incrementales.
     * Este mapeo asegura que cada cuenta única tenga un solo número de referencia,
     * el cual se reutilizará si la cuenta aparece múltiples veces.
     *
     * @param \Illuminate\Database\Eloquent\Collection $asientos Colección de asientos con sus detalles y cuentas contables.
     * @return array Un array asociativo donde la clave es el ID de la cuenta y el valor es su número de referencia.
     */
    public function getUniqueAccountReferences($asientos)
    {
        $accountReferences = []; // Almacenará el mapeo final: [cuenta_id => numero_referencia]
        $refCounter = 1;        // Contador para asignar los números de referencia

        // Recorrer todos los asientos y sus detalles para asignar referencias
        // en el orden en que las cuentas son encontradas por primera vez.
        foreach ($asientos as $asiento) {
            foreach ($asiento->detalles as $detalle) {
                if ($detalle->cuentaContable) {
                    $accountId = $detalle->cuentaContable->id_cuenta;
                    // Si la cuenta aún no tiene una referencia asignada, le asignamos una nueva.
                    if (!isset($accountReferences[$accountId])) {
                        $accountReferences[$accountId] = $refCounter++;
                    }
                }
            }
        }

        return $accountReferences;
    }

    public function generarLibroDiario(Request $request)
    {
        $query = Asiento::with('detalles.cuentaContable')
                        ->orderBy('fecha', 'asc');

        if ($request->filled('fecha_inicio')) {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->where('fecha', '<=', $request->fecha_fin);
        }

        $asientos = $query->get();
        $accountReferences = $this->getUniqueAccountReferences($asientos); // Obtener referencias únicas

        return view('contabilidad.libro-diario', compact('asientos', 'accountReferences'));
    }

    public function generarLibroDiarioPDF(Request $request)
    {
        $query = Asiento::with('detalles.cuentaContable')
                        ->orderBy('fecha', 'asc');

        if ($request->filled('fecha_inicio')) {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->where('fecha', '<=', $request->fecha_fin);
        }

        $asientos = $query->get();
        $accountReferences = $this->getUniqueAccountReferences($asientos); // Obtener referencias únicas

        // Cargar la vista específica para el PDF con los datos.
        $pdf = Pdf::loadView('contabilidad.libro-diario-pdf', compact('asientos', 'accountReferences'));

        // Servir el PDF al navegador para descarga o visualización.
        return $pdf->stream('reporte_libro_diario_' . date('Y-m-d') . '.pdf');
    }

    protected function getAsientoSequentialNumbers($asientos)
    {
        $sequentialNumbers = [];
        $counter = 1;
        foreach ($asientos as $asiento) {
            $sequentialNumbers[$asiento->id_asiento] = $counter++;
        }
        return $sequentialNumbers;
    }

    public function generarLibroMayor(Request $request)
    {
        // Obtener todos los asientos con sus detalles y cuentas contables
        $queryAsientos = Asiento::with('detalles.cuentaContable')
                                ->orderBy('fecha', 'asc')
                                ->orderBy('id_asiento', 'asc'); // Ordenar por fecha y luego por ID de asiento

        if ($request->filled('fecha_inicio')) {
            $queryAsientos->where('fecha', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $queryAsientos->where('fecha', '<=', $request->fecha_fin);
        }

        $asientos = $queryAsientos->get();

        // Obtener las referencias únicas de las cuentas del Libro Diario
        // Esto asegura que el orden de las cuentas en el Libro Mayor sea el mismo que en el Diario
        $accountReferences = $this->getUniqueAccountReferences($asientos);
        // Obtener los números secuenciales de los asientos para la referencia en el Libro Mayor
        $asientoSequentialNumbers = $this->getAsientoSequentialNumbers($asientos);

        $libroMayorData = [];
        $currentBalances = []; // Para mantener el saldo acumulado de cada cuenta

        // Inicializar la estructura del libro mayor y los saldos actuales
        // Recorremos las referencias ordenadas para mantener el orden del Diario
        foreach ($accountReferences as $accountId => $reference) {
            $cuenta = CuentaContable::find($accountId); // Obtener el objeto CuentaContable
            if ($cuenta) {
                $libroMayorData[$accountId] = [
                    'codigo' => $cuenta->codigo,
                    'nombre' => $cuenta->nombre,
                    'referencia_cuenta' => $reference,
                    'tipo' => $cuenta->tipo, // Almacenar el tipo de cuenta
                    'movimientos' => [],
                    'total_debe_cuenta' => 0, // Inicializar totales por cuenta
                    'total_haber_cuenta' => 0, // Inicializar totales por cuenta
                ];
                $currentBalances[$accountId] = 0; // Saldo inicial para el reporte
            }
        }

        // Procesar los asientos para llenar los movimientos de cada cuenta
        foreach ($asientos as $asiento) {
            foreach ($asiento->detalles as $detalle) {
                if ($detalle->cuentaContable) {
                    $accountId = $detalle->cuentaContable->id_cuenta;

                    // Asegurarse de que la cuenta existe en nuestro libroMayorData (ya inicializada)
                    if (isset($libroMayorData[$accountId])) {
                        $tipoCuenta = $libroMayorData[$accountId]['tipo']; // Obtener el tipo de cuenta

                        // Actualizar el saldo acumulado según el tipo de cuenta
                        if (in_array($tipoCuenta, ['activo', 'egreso', 'costo'])) {
                            $currentBalances[$accountId] += $detalle->debe;
                            $currentBalances[$accountId] -= $detalle->haber;
                        } else { // 'pasivo', 'patrimonio', 'ingreso'
                            $currentBalances[$accountId] += $detalle->haber;
                            $currentBalances[$accountId] -= $detalle->debe;
                        }

                        // Acumular totales de Debe y Haber para la cuenta actual
                        $libroMayorData[$accountId]['total_debe_cuenta'] += $detalle->debe;
                        $libroMayorData[$accountId]['total_haber_cuenta'] += $detalle->haber;

                        // Añadir el movimiento al array de movimientos de la cuenta
                        $libroMayorData[$accountId]['movimientos'][] = [
                            'fecha' => $asiento->fecha,
                            'asiento_ref_diario' => $asientoSequentialNumbers[$asiento->id_asiento],
                            'descripcion_asiento' => $asiento->descripcion,
                            'debe' => $detalle->debe,
                            'haber' => $detalle->haber,
                            'balance_acumulado' => $currentBalances[$accountId],
                        ];
                    }
                }
            }
        }

        // Convertir el array asociativo a un array indexado para facilitar el bucle en la vista
        // y mantener el orden por referencia de cuenta
        $sortedLibroMayorData = collect($libroMayorData)->sortBy('referencia_cuenta')->values()->all();

        return view('contabilidad.libro-mayor', compact('sortedLibroMayorData'));
    }

    public function generarLibroMayorPDF(Request $request)
    {
        // Obtener todos los asientos con sus detalles y cuentas contables
        $queryAsientos = Asiento::with('detalles.cuentaContable')
                                ->orderBy('fecha', 'asc')
                                ->orderBy('id_asiento', 'asc');

        if ($request->filled('fecha_inicio')) {
            $queryAsientos->where('fecha', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $queryAsientos->where('fecha', '<=', $request->fecha_fin);
        }

        $asientos = $queryAsientos->get();

        $accountReferences = $this->getUniqueAccountReferences($asientos);
        $asientoSequentialNumbers = $this->getAsientoSequentialNumbers($asientos);

        $libroMayorData = [];
        $currentBalances = [];

        foreach ($accountReferences as $accountId => $reference) {
            $cuenta = CuentaContable::find($accountId);
            if ($cuenta) {
                $libroMayorData[$accountId] = [
                    'codigo' => $cuenta->codigo,
                    'nombre' => $cuenta->nombre,
                    'referencia_cuenta' => $reference,
                    'tipo' => $cuenta->tipo,
                    'movimientos' => [],
                    'total_debe_cuenta' => 0,
                    'total_haber_cuenta' => 0,
                ];
                $currentBalances[$accountId] = 0;
            }
        }

        foreach ($asientos as $asiento) {
            foreach ($asiento->detalles as $detalle) {
                if ($detalle->cuentaContable) {
                    $accountId = $detalle->cuentaContable->id_cuenta;

                    if (isset($libroMayorData[$accountId])) {
                        $tipoCuenta = $libroMayorData[$accountId]['tipo'];

                        if (in_array($tipoCuenta, ['activo', 'egreso', 'costo'])) {
                            $currentBalances[$accountId] += $detalle->debe;
                            $currentBalances[$accountId] -= $detalle->haber;
                        } else {
                            $currentBalances[$accountId] += $detalle->haber;
                            $currentBalances[$accountId] -= $detalle->debe;
                        }

                        $libroMayorData[$accountId]['total_debe_cuenta'] += $detalle->debe;
                        $libroMayorData[$accountId]['total_haber_cuenta'] += $detalle->haber;

                        $libroMayorData[$accountId]['movimientos'][] = [
                            'fecha' => $asiento->fecha,
                            'asiento_ref_diario' => $asientoSequentialNumbers[$asiento->id_asiento],
                            'descripcion_asiento' => $asiento->descripcion,
                            'debe' => $detalle->debe,
                            'haber' => $detalle->haber,
                            'balance_acumulado' => $currentBalances[$accountId],
                        ];
                    }
                }
            }
        }

        $sortedLibroMayorData = collect($libroMayorData)->sortBy('referencia_cuenta')->values()->all();

        // Cargar la vista específica para el PDF con los datos.
        $pdf = Pdf::loadView('contabilidad.libro-mayor-pdf', compact('sortedLibroMayorData'));

        // Servir el PDF al navegador para descarga o visualización.
        return $pdf->stream('reporte_libro_mayor_' . date('Y-m-d') . '.pdf');
    }

public function generarBalanceComprobacion(Request $request)
    {
        // Obtener todos los detalles de asiento para identificar las cuentas con movimientos
        $queryDetalles = DetalleAsiento::with('cuentaContable', 'asientoContable');

        // Aquí podrías añadir lógica de filtrado por fecha si es necesario para el balance
        // Ejemplo:
        /*
        if ($request->filled('fecha_inicio')) {
            $queryDetalles->whereHas('asientoContable', function ($q) use ($request) {
                $q->where('fecha', '>=', $request->fecha_inicio);
            });
        }
        if ($request->filled('fecha_fin')) {
            $queryDetalles->whereHas('asientoContable', function ($q) use ($request) {
                $q->where('fecha', '<=', $request->fecha_fin);
            });
        }
        */

        $detallesConMovimientos = $queryDetalles->get();

        // Agrupar los movimientos por cuenta contable
        $cuentasConMovimientos = [];
        foreach ($detallesConMovimientos as $detalle) {
            if ($detalle->cuentaContable) {
                $accountId = $detalle->cuentaContable->id_cuenta;
                if (!isset($cuentasConMovimientos[$accountId])) {
                    $cuentasConMovimientos[$accountId] = [
                        'cuenta' => $detalle->cuentaContable,
                        'total_debe' => 0,
                        'total_haber' => 0,
                    ];
                }
                $cuentasConMovimientos[$accountId]['total_debe'] += $detalle->debe;
                $cuentasConMovimientos[$accountId]['total_haber'] += $detalle->haber;
            }
        }

        // Definir el orden deseado de los tipos de cuenta
        $typeOrder = [
            'activo' => 1,
            'pasivo' => 2,
            'patrimonio' => 3,
            'ingreso' => 4,
            'egreso' => 5,
            'costo' => 6,
        ];

        // Convertir el array asociativo a una colección y ordenar primero por el índice del tipo, luego por código
        $sortedCuentasConMovimientos = collect($cuentasConMovimientos)->sortBy(function ($item) use ($typeOrder) {
            $order = $typeOrder[$item['cuenta']->tipo] ?? 99; // Asignar un número alto si el tipo no está en la lista
            return [$order, $item['cuenta']->codigo]; // Ordenar por array: primero por orden de tipo, luego por código
        })->values();

        $balanceData = [];
        $granTotalMovimientosDebe = 0;
        $granTotalMovimientosHaber = 0;
        $granTotalSaldoDebe = 0;
        $granTotalSaldoHaber = 0;

        foreach ($sortedCuentasConMovimientos as $item) {
            $cuenta = $item['cuenta'];
            $totalDebeCuenta = $item['total_debe'];
            $totalHaberCuenta = $item['total_haber'];

            $saldoFinalDebe = 0;
            $saldoFinalHaber = 0;

            // Calcular el saldo final según el tipo de cuenta
            if (in_array($cuenta->tipo, ['activo', 'egreso', 'costo'])) {
                $saldo = $totalDebeCuenta - $totalHaberCuenta;
                if ($saldo > 0) {
                    $saldoFinalDebe = $saldo;
                } else {
                    $saldoFinalHaber = abs($saldo); // Si es negativo, va en Haber
                }
            } else { // Pasivo, Patrimonio, Ingreso
                $saldo = $totalHaberCuenta - $totalDebeCuenta;
                if ($saldo > 0) {
                    $saldoFinalHaber = $saldo;
                } else {
                    $saldoFinalDebe = abs($saldo); // Si es negativo, va en Debe
                }
            }

            $balanceData[] = [
                'codigo' => $cuenta->codigo,
                'nombre' => $cuenta->nombre,
                'total_movimientos_debe' => $totalDebeCuenta,
                'total_movimientos_haber' => $totalHaberCuenta,
                'saldo_final_debe' => $saldoFinalDebe,
                'saldo_final_haber' => $saldoFinalHaber,
            ];

            $granTotalMovimientosDebe += $totalDebeCuenta;
            $granTotalMovimientosHaber += $totalHaberCuenta;
            $granTotalSaldoDebe += $saldoFinalDebe;
            $granTotalSaldoHaber += $saldoFinalHaber;
        }

        return view('contabilidad.balance-comprobacion', compact(
            'balanceData',
            'granTotalMovimientosDebe',
            'granTotalMovimientosHaber',
            'granTotalSaldoDebe',
            'granTotalSaldoHaber'
        ));
    }

    public function generarBalanceComprobacionPDF(Request $request)
    {
        // Obtener todos los detalles de asiento para identificar las cuentas con movimientos
        $queryDetalles = DetalleAsiento::with('cuentaContable', 'asientoContable');

        // Aquí podrías añadir lógica de filtrado por fecha si es necesario para el balance
        // Ejemplo:
        /*
        if ($request->filled('fecha_inicio')) {
            $queryDetalles->whereHas('asientoContable', function ($q) use ($request) {
                $q->where('fecha', '>=', $request->fecha_inicio);
            });
        }
        if ($request->filled('fecha_fin')) {
            $queryDetalles->whereHas('asientoContable', function ($q) use ($request) {
                $q->where('fecha', '<=', $request->fecha_fin);
            });
        }
        */

        $detallesConMovimientos = $queryDetalles->get();

        // Agrupar los movimientos por cuenta contable
        $cuentasConMovimientos = [];
        foreach ($detallesConMovimientos as $detalle) {
            if ($detalle->cuentaContable) {
                $accountId = $detalle->cuentaContable->id_cuenta;
                if (!isset($cuentasConMovimientos[$accountId])) {
                    $cuentasConMovimientos[$accountId] = [
                        'cuenta' => $detalle->cuentaContable,
                        'total_debe' => 0,
                        'total_haber' => 0,
                    ];
                }
                $cuentasConMovimientos[$accountId]['total_debe'] += $detalle->debe;
                $cuentasConMovimientos[$accountId]['total_haber'] += $detalle->haber;
            }
        }

        // Definir el orden deseado de los tipos de cuenta
        $typeOrder = [
            'activo' => 1,
            'pasivo' => 2,
            'patrimonio' => 3,
            'ingreso' => 4,
            'egreso' => 5,
            'costo' => 6,
        ];

        // Convertir el array asociativo a una colección y ordenar primero por el índice del tipo, luego por código
        $sortedCuentasConMovimientos = collect($cuentasConMovimientos)->sortBy(function ($item) use ($typeOrder) {
            $order = $typeOrder[$item['cuenta']->tipo] ?? 99; // Asignar un número alto si el tipo no está en la lista
            return [$order, $item['cuenta']->codigo]; // Ordenar por array: primero por orden de tipo, luego por código
        })->values();

        $balanceData = [];
        $granTotalMovimientosDebe = 0;
        $granTotalMovimientosHaber = 0;
        $granTotalSaldoDebe = 0;
        $granTotalSaldoHaber = 0;

        foreach ($sortedCuentasConMovimientos as $item) {
            $cuenta = $item['cuenta'];
            $totalDebeCuenta = $item['total_debe'];
            $totalHaberCuenta = $item['total_haber'];

            $saldoFinalDebe = 0;
            $saldoFinalHaber = 0;

            // Calcular el saldo final según el tipo de cuenta
            if (in_array($cuenta->tipo, ['activo', 'egreso', 'costo'])) {
                $saldo = $totalDebeCuenta - $totalHaberCuenta;
                if ($saldo > 0) {
                    $saldoFinalDebe = $saldo;
                } else {
                    $saldoFinalHaber = abs($saldo); // Si es negativo, va en Haber
                }
            } else { // Pasivo, Patrimonio, Ingreso
                $saldo = $totalHaberCuenta - $totalDebeCuenta;
                if ($saldo > 0) {
                    $saldoFinalHaber = $saldo;
                } else {
                    $saldoFinalDebe = abs($saldo); // Si es negativo, va en Debe
                }
            }

            $balanceData[] = [
                'codigo' => $cuenta->codigo,
                'nombre' => $cuenta->nombre,
                'total_movimientos_debe' => $totalDebeCuenta,
                'total_movimientos_haber' => $totalHaberCuenta,
                'saldo_final_debe' => $saldoFinalDebe,
                'saldo_final_haber' => $saldoFinalHaber,
            ];

            $granTotalMovimientosDebe += $totalDebeCuenta;
            $granTotalMovimientosHaber += $totalHaberCuenta;
            $granTotalSaldoDebe += $saldoFinalDebe;
            $granTotalSaldoHaber += $saldoFinalHaber; // ¡Esta es la línea corregida!
        }

        // Cargar la vista específica para el PDF con los datos.
        $pdf = Pdf::loadView('contabilidad.balance-comprobacion-pdf', compact(
            'balanceData',
            'granTotalMovimientosDebe',
            'granTotalMovimientosHaber',
            'granTotalSaldoDebe',
            'granTotalSaldoHaber'
        ));

        // Servir el PDF al navegador para descarga o visualización.
        return $pdf->stream('reporte_balance_comprobacion_' . date('Y-m-d') . '.pdf');
    }
}