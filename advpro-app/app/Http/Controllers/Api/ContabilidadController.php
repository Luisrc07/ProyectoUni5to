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
}