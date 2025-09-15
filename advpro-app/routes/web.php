<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\ContratoController;
use App\Http\Controllers\Api\ProyectoController;
use App\Http\Controllers\Api\PersonalController;
use App\Http\Controllers\Api\EquipoController;
use App\Http\Controllers\Api\ContabilidadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Definimos aquí todas las rutas de la aplicación.
|
*/

Auth::routes();


Route::get('/', function () {
    return redirect()->route('login');
})->name('root');

// Página de inicio pública
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');
// ... (El resto de tus rutas, incluyendo Auth::routes() y las rutas protegidas)

// 2. Rutas protegidas (auth) - Solo accesibles para usuarios autenticados
Route::middleware('auth')->group(function () {
    
    // Rutas de Dashboard / Home
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/inicio/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Reportes (Definidas antes de los recursos para evitar conflictos)
    Route::get('clientes/reporte', [ClienteController::class, 'generarReporte'])->name('clientes.reporte');
    Route::get('equipos/reporte', [EquipoController::class, 'generarReporte'])->name('equipos.reporte');
    Route::get('proyectos/exportar-pdf', [ProyectoController::class, 'exportarPdf'])->name('proyectos.exportar-pdf');
    Route::get('personal/reporte-pdf', [PersonalController::class, 'exportarPdf'])->name('personal.exportar-pdf');
    Route::get('contratos/{contrato}/pdf', [ContratoController::class, 'generarPdf'])->name('contratos.pdf');

    // Recursos (CRUD)
    Route::resource('clientes', ClienteController::class);
    Route::resource('contratos', ContratoController::class);
    Route::resource('proyectos', ProyectoController::class);
    Route::resource('personal', PersonalController::class);
    Route::resource('equipos', EquipoController::class);

    // Contabilidad
    Route::prefix('contabilidad')->group(function () {
        Route::get('panel', [ContabilidadController::class, 'index'])->name('contabilidad.index');
        Route::post('asientos', [ContabilidadController::class, 'store'])->name('contabilidad.store');
        Route::post('cuentas', [ContabilidadController::class, 'storeCuenta'])->name('contabilidad.cuentas.store');
        Route::get('cuentas/por-tipo/{tipo}', [ContabilidadController::class, 'getCuentasPorTipo'])->name('contabilidad.cuentas.porTipo');
        Route::get('cuentas/siguiente-codigo', [ContabilidadController::class, 'getSiguienteCodigo'])->name('contabilidad.cuentas.siguienteCodigo');
        Route::get('reportes/libro-diario', [ContabilidadController::class, 'generarLibroDiario'])->name('contabilidad.libroDiario');
        Route::get('reportes/libro-diario-pdf', [ContabilidadController::class, 'generarLibroDiarioPDF'])->name('contabilidad.libroDiarioPDF');
        Route::get('reportes/libro-mayor', [ContabilidadController::class, 'generarLibroMayor'])->name('contabilidad.libroMayor');
        Route::get('reportes/libro-mayor-pdf', [ContabilidadController::class, 'generarLibroMayorPDF'])->name('contabilidad.libroMayorPDF');
        Route::get('reportes/balance-comprobacion', [ContabilidadController::class, 'generarBalanceComprobacion'])->name('contabilidad.balanceComprobacion');
        Route::get('reportes/balance-comprobacion-pdf', [ContabilidadController::class, 'generarBalanceComprobacionPDF'])->name('contabilidad.balanceComprobacionPDF');
        Route::get('/dashboard/obtener-cuentas', [DashboardController::class, 'obtenerCuentasPorTipo'])->name('dashboard.obtener-cuentas');
        Route::post('/dashboard/obtener-datos-grafico', [DashboardController::class, 'obtenerDatosGrafico'])->name('dashboard.obtener-datos-grafico');
        Route::delete('asientos/{asiento}', [ContabilidadController::class, 'destroy'])->name('contabilidad.destroy');
    });
});

// 3. Fallback: cualquier URI desconocida redirige a la raíz (`/`).
Route::fallback(fn() => redirect()->route('root'));