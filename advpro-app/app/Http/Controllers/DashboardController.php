<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Contrato;
use Illuminate\Support\Carbon;
use App\Models\CuentaContable;
use App\Models\DetalleAsiento;
use Illuminate\Validation\Rule;
use DateTime;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Datos para las tarjetas
        $totalClientes = Cliente::count();
        
        $estadisticasContratos = Contrato::selectRaw('
            SUM(costo) as balance_total,
            SUM(CASE WHEN estado = "activo" THEN 1 ELSE 0 END) as contratos_activos,
            SUM(CASE WHEN estado = "finalizado" THEN 1 ELSE 0 END) as contratos_finalizados
        ')->first();

        // 2. Datos para gráfico de barras (con año actual)
        $yearActual = Carbon::now()->year;
        $contratosPorMes = Contrato::selectRaw('
            DATE_FORMAT(fecha_contrato, "%b") as mes_abrev,
            COUNT(*) as total
        ')
        ->whereYear('fecha_contrato', $yearActual)
        ->groupBy('mes_abrev')
        ->get();

        $todosLosMeses = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $datosGrafico = [];
        foreach ($todosLosMeses as $mes) {
            $registro = $contratosPorMes->firstWhere('mes_abrev', $mes);
            $datosGrafico[$mes] = $registro ? $registro->total : 0;
        }

        return view('inicio.dashboard', [
            // Datos para tarjetas
            'totalClientes' => $totalClientes,
            'balanceContratos' => $estadisticasContratos->balance_total ?? 0,
            'contratosActivos' => $estadisticasContratos->contratos_activos ?? 0,
            'contratosFinalizados' => $estadisticasContratos->contratos_finalizados ?? 0,
            
            // Datos para gráfico
            'mesesGrafico' => array_keys($datosGrafico),
            'valoresGrafico' => array_values($datosGrafico),
            
            // Año actual para el título
            'yearActual' => $yearActual
        ]);
    }

    public function obtenerCuentasPorTipo(Request $request)
    {
        $validTypes = ['activo', 'pasivo', 'egreso', 'ingreso', 'costo', 'patrimonio'];
        
        $request->validate([
            'tipo' => ['required', 'string', Rule::in($validTypes)]
        ]);
        
        try {
            $cuentas = CuentaContable::where('tipo', $request->tipo)
                        ->select('id_cuenta', 'codigo', 'nombre')
                        ->orderBy('codigo')
                        ->get();
            
            return response()->json($cuentas);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener las cuentas',
                'message' => $e->getMessage()
            ], 500);
        }
    }

public function obtenerDatosGrafico(Request $request)
{
    $request->validate([
        'cuenta_id' => 'required|numeric',
        'tipo_grafico' => 'required|in:bar,line,pie'
    ]);

    $cuenta = CuentaContable::findOrFail($request->cuenta_id);
    $year = date('Y');

    // Consulta corregida usando el nombre exacto de la relación
    $datos = DetalleAsiento::with('asientoContable')
        ->where('id_cuenta', $request->cuenta_id)
        ->whereHas('asientoContable', function($query) use ($year) {
            $query->whereYear('fecha', $year);
        })
        ->selectRaw('
            MONTH(asiento_contable.fecha) as mes,
            SUM(detalle_asiento.debe) as total_debe,
            SUM(detalle_asiento.haber) as total_haber
        ')
        ->join('asiento_contable', 'detalle_asiento.id_asiento', '=', 'asiento_contable.id_asiento')
        ->groupByRaw('MONTH(asiento_contable.fecha)')
        ->orderBy('mes')
        ->get();

        // Procesar para gráfico
        $labels = [];
        $debeData = [];
        $haberData = [];
        $totalDebe = 0;
        $totalHaber = 0;

        for ($mes = 1; $mes <= 12; $mes++) {
            $nombreMes = DateTime::createFromFormat('!m', $mes)->format('F');
            $labels[] = $nombreMes;
            $mesDatos = $datos->firstWhere('mes', $mes);
            
            $debe = $mesDatos ? (float)$mesDatos->total_debe : 0;
            $haber = $mesDatos ? (float)$mesDatos->total_haber : 0;
            
            $debeData[] = $debe;
            $haberData[] = $haber;
            
            $totalDebe += $debe;
            $totalHaber += $haber;
        }

        return response()->json([
            'labels' => $labels,
            'debeData' => $debeData,
            'haberData' => $haberData,
            'total_debe' => $totalDebe,
            'total_haber' => $totalHaber,
            'tipo_grafico' => $request->tipo_grafico,
            'titulo' => "Movimientos de {$cuenta->nombre} ({$cuenta->codigo}) - {$year}"
        ]);
    }
    // ... (otros métodos)
}