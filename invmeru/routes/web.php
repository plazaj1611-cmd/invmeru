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
Route::middleware('rol:admin')->group(function () {
    // Consultas
    Route::get('/consulta', [ConsultsController::class, 'indexadmin'])->name('consultar.producto');
    Route::post('/consulta', [ConsultsController::class, 'consultarAdmin'])->name('consultar');

    // Retirar
    Route::get('/repuestos/{id}/retirar', [RepuestosController::class, 'retirarFormAdmin'])->name('repuestos.retirar.form');
    Route::post('/repuestos/retirar', [RepuestosController::class, 'retirarExistenciaAdmin'])->name('repuestos.retirar');

    //Inventario
    Route::get('/inventario', [RepuestosController::class, 'inventario'])->name('inventario.index');

    // Reportes
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::post('/reports/general', [ReportsController::class, 'generarGeneral'])->name('reports.general');
    Route::post('/reports/detallado', [ReportsController::class, 'generarDetallado'])->name('reports.detallado');

    // Administración
    Route::get('/admin', [AdminController::class, 'index'])->name('administrador');

    //Crear Registro
    Route::get('/admin/crear_registro', [AdminController::class, 'create'])->name('crear.registro');
    Route::post('/repuestos', [RepuestosController::class, 'store'])->name('repuestos.store');

    //Agregar existencia
    Route::get('/admin/agregar_existencia', [AdminController::class, 'addExistence'])->name('agregar.existencias');
    Route::post('/repuestos/agregar', [RepuestosController::class, 'agregarExistencia'])->name('repuestos.agregar');
    Route::get('/api/repuestos/info', [RepuestosController::class, 'infoRepuesto']);

    //Activar - Desactivar
    Route::patch('/inventario/{repuesto}/toggle', [RepuestosController::class, 'toggleEstado'])->name('repuestos.toggle');

    // Gestión de usuarios

    //Cambiar Pin
    Route::post('/usuarios/cambiar-pin', [UserController::class, 'cambiarPin'])->name('cambiar.pin');

    //Administrar usuarios 
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

    // Movimientos
    Route::get('/movimientos/create', [MovementController::class, 'create'])->name('movimientos.create');
    Route::post('/movimientos', [MovementController::class, 'store'])->name('movimientos.store');
});

// Rutas protegidas para usuarios normales
Route::middleware('rol:usuario')->group(function () {

    //Consulta
    Route::get('/consulta/normal', [ConsultsController::class, 'indexnormal'])->name('consulta.normal');
    Route::post('/consulta/normal', [ConsultsController::class, 'consultarNormal'])->name('consultar.normal');
    
    //Retirar
    Route::get('/consulta/normal/{id}/retirar', [RepuestosController::class, 'retirarFormNormal'])->name('retirar.normal.form');
    Route::post('/consulta/normal/retirar', [RepuestosController::class, 'retirarExistenciaNormal'])->name('retirar.normal');
});
