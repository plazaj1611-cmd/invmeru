<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConsultsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\AutenController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RepuestosController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EntradaRepuestosController;
use App\Http\Controllers\DepositoController;
use App\Models\Repuesto;

// Login
Route::get('/', [AutenController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AutenController::class, 'procesarLogin'])->name('login.procesar');

// Home
Route::get('/home', function () {
    if (session('rol') === 'admin') {
        return view('home'); 
    } elseif (session('rol') === 'user' || session('rol') === 'usuario') {
        return redirect()->route('consulta.normal'); 
    }

    abort(403);
})->name('home');

// Autocompletado (disponible para todos)
Route::get('/api/repuestos/buscar', [RepuestosController::class, 'buscarAjax'])->name('repuestos.buscar');

// Rutas protegidas para administradores
Route::middleware(['auth', 'rol:admin'])->group(function () {
    // Consultas
    Route::get('/consulta', [ConsultsController::class, 'indexadmin'])->name('consultar.producto');
    Route::post('/consulta', [ConsultsController::class, 'consultarAdmin'])->name('consultar');

    // Retirar
    Route::get('/repuestos/{id}/retirar', [RepuestosController::class, 'retirarFormAdmin'])->name('repuestos.retirar.form');
    Route::post('/repuestos/retirar', [RepuestosController::class, 'retirarExistenciaAdmin'])->name('repuestos.retirar');
    Route::get('/repuestos/{id}/depositos', [RepuestosController::class, 'obtenerDepositosPorRepuesto']);


    // Inventario
    Route::get('/inventario', [RepuestosController::class, 'inventario'])->name('inventario.index');

    // Reportes
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::post('/reports/general', [ReportsController::class, 'generarGeneral'])->name('reports.general');
    Route::post('/reports/detallado', [ReportsController::class, 'generarDetallado'])->name('reports.detallado');
    Route::get('/reports/hoy', [ReportsController::class, 'hoy'])->name('reports.hoy');
    Route::get('/reports/ayer', [ReportsController::class, 'ayer'])->name('reports.ayer');

    // Depositos
    Route::get('/depositos', [DepositoController::class, 'index'])->name('depositos.index');
    Route::get('/depositos/{id}', [DepositoController::class, 'show'])->name('depositos.show');
    Route::post('/depositos', [DepositoController::class, 'store'])->name('depositos.store');

    // Administración
    Route::get('/admin', [AdminController::class, 'index'])->name('administrador');

    // Crear Registro
    Route::get('/admin/crear_registro', [AdminController::class, 'create'])->name('crear.registro');
    Route::post('/repuestos', [RepuestosController::class, 'store'])->name('repuestos.store');

    // Agregar existencia
    Route::get('/admin/agregar_existencia', [EntradaRepuestosController::class, 'create'])->name('agregar.existencias');
    Route::post('/admin/agregar_existencia', [EntradaRepuestosController::class, 'store'])->name('entrada.store');
    Route::get('/api/repuestos/info', [RepuestosController::class, 'infoRepuesto']);

    // Ver Entradas
    Route::get('/repuestos/{id}/entradas', [EntradaRepuestosController::class, 'getEntradas']);

    // Activar - Desactivar
    Route::patch('/inventario/{repuesto}/toggle', [RepuestosController::class, 'toggleEstado'])->name('repuestos.toggle');

// Gestión de usuarios
    // Cambiar Pin
    Route::post('/usuarios/cambiar-pin', [UserController::class, 'cambiarPin'])->name('cambiar.pin');

    // Administrar usuarios 
    Route::prefix('usuarios')->group(function () {

    // Mostrar todos los usuarios
    Route::get('/', [UserController::class, 'index'])->name('usuarios.index');

    // Crear usuario
    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios/crear/post', [UserController::class, 'store'])->name('usuarios.store');

    // Editar usuario
    Route::get('/usuarios/{user}/editar', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('usuarios.update');

    // Activar/Desactivar Usuario
    Route::patch('/usuarios/{user}/toggle', [UserController::class, 'toggleEstado'])->name('usuarios.toggle');

});

    // CRUD Repuestos
    Route::resource('repuestos', RepuestosController::class);

});

// Rutas protegidas para usuarios normales
Route::middleware(['auth', 'rol:usuario'])->group(function () {

    //Consulta
    Route::get('/consulta/normal', [ConsultsController::class, 'indexnormal'])->name('consulta.normal');
    Route::post('/consulta/normal', [ConsultsController::class, 'consultarNormal'])->name('consultar.normal');

    // Ver Entradas
    Route::get('/repuestos/{id}/entradasn', [EntradaRepuestosController::class, 'getEntradas']);
    
    //Retirar
    Route::get('/consulta/normal/{id}/retirar', [RepuestosController::class, 'retirarFormNormal'])->name('retirar.normal.form');
    Route::post('/consulta/normal/retirar', [RepuestosController::class, 'retirarExistenciaNormal'])->name('retirar.normal');
    Route::get('/repuestos/{id}/depositosn', [RepuestosController::class, 'obtenerDepositosPorRepuesto']);
});
