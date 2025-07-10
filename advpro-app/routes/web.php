<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
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
| - Las rutas de invitado (guest) solo las ven los no autenticados.
| - Las rutas de auth solo las ven los autenticados.
| - Cualquier otra ruta desconocida dispara el fallback.
|
*/
Auth::routes();

// 1. Ruta raíz para invitados: redirige a /login.
//    Si ya estás autenticado, el middleware 'guest' te enviará a /home.
Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest')->name('root');


// 2. Rutas de autenticación (guest) - Solo accesibles para usuarios no autenticados
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Registro
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // (Opcional) Recuperación de contraseña...
});


// 3. Rutas protegidas (auth) - Solo accesibles para usuarios autenticados
Route::middleware('auth')->group(function () {
    // Dashboard / Home
    Route::get('/home',           [DashboardController::class, 'index'])->name('home');
    Route::get('/inicio/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout (POST)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Reportes (Definidas antes de los recursos para evitar conflictos si las URLs se superponen)
    Route::get('clientes/reporte',      [ClienteController::class,  'generarReporte'])->name('clientes.reporte');
    Route::get('equipos/reporte',       [EquipoController::class,   'generarReporte'])->name('equipos.reporte');
    Route::get('proyectos/exportar-pdf', [ProyectoController::class,'exportarPdf'])->name('proyectos.exportar-pdf');
    Route::get('personal/reporte-pdf',   [PersonalController::class,'exportarPdf'])->name('personal.exportar-pdf');
    
    // Nueva ruta para generar el PDF del contrato
    Route::get('contratos/{contrato}/pdf', [ContratoController::class, 'generarPdf'])->name('contratos.pdf');


    // Recursos (CRUD completo para cada modelo)
    // Estas rutas generarán métodos como index, create, store, show, edit, update, destroy.
    Route::resource('clientes',  ClienteController::class);
    Route::resource('contratos', ContratoController::class);
    Route::resource('proyectos', ProyectoController::class);
    Route::resource('personal',  PersonalController::class);
    Route::resource('equipos',   EquipoController::class);


    //Contabilidad
    //(Se usa prefix para poder manejar mas funciones en el controlador
    //Fuera de los predefinidos, y el manejo de varios modelos.)
    Route::prefix('contabilidad')->group(function () {
    
        // Ruta para el panel principal de contabilidad
        Route::get('panel', [ContabilidadController::class, 'index'])->name('contabilidad.index');
    
        // Ruta para guardar un nuevo asiento contable
        Route::post('asientos', [ContabilidadController::class, 'store'])->name('contabilidad.store');
    
        // NUEVA RUTA: Para guardar una nueva cuenta contable
        Route::post('cuentas', [ContabilidadController::class, 'storeCuenta'])->name('contabilidad.cuentas.store');
    
        // 1. Devuelve cuentas padre filtradas por tipo (para el select dinámico)
        Route::get('cuentas/por-tipo/{tipo}', [ContabilidadController::class, 'getCuentasPorTipo'])->name('contabilidad.cuentas.porTipo');
        
        // 2. Devuelve el siguiente código disponible basado en la cuenta padre (o el tipo)
        Route::get('cuentas/siguiente-codigo', [ContabilidadController::class, 'getSiguienteCodigo'])->name('contabilidad.cuentas.siguienteCodigo');
        Route::get('reportes/libro-diario', [ContabilidadController::class, 'generarLibroDiario'])->name('contabilidad.libroDiario');
    
        Route::get('reportes/libro-diario-pdf', [ContabilidadController::class, 'generarLibroDiarioPDF'])->name('contabilidad.libroDiarioPDF');
        
        Route::get('reportes/libro-mayor', [ContabilidadController::class, 'generarLibroMayor'])->name('contabilidad.libroMayor');
    
        Route::get('reportes/libro-mayor-pdf', [ContabilidadController::class, 'generarLibroMayorPDF'])->name('contabilidad.libroMayorPDF');

        Route::get('reportes/balance-comprobacion', [ContabilidadController::class, 'generarBalanceComprobacion'])->name('contabilidad.balanceComprobacion');
    
        Route::get('reportes/balance-comprobacion-pdf', [ContabilidadController::class, 'generarBalanceComprobacionPDF'])->name('contabilidad.balanceComprobacionPDF');

        // Datos para graficos en el dashboard
        Route::get('/dashboard/obtener-cuentas', [DashboardController::class, 'obtenerCuentasPorTipo'])->name('dashboard.obtener-cuentas');
        // Ruta para obtener datos del gráfico
        Route::post('/dashboard/obtener-datos-grafico', [DashboardController::class, 'obtenerDatosGrafico'])->name('dashboard.obtener-datos-grafico');

        Route::delete('asientos/{asiento}', [ContabilidadController::class, 'destroy'])->name('contabilidad.destroy');
        
        
       

});


});
// 4. Ruta pública de bienvenida (accesible para todos)
Route::get('/welcome', fn() => view('welcome'))->name('welcome');


// 5. Fallback: cualquier otra URI desconocida redirige a la raíz (`/`),
//    que a su vez enviará al invitado al login, o al usuario autenticado al home.
Route::fallback(fn() => redirect()->route('root'));
