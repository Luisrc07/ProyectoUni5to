<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Contrato;
use Illuminate\Support\Carbon;

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

    // ... (otros métodos)
}