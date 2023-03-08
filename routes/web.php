<?php

declare(strict_types=1);

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AcessoController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['permission:utilitarios.permissoes'])->group(function () {
        Route::post('/permissions/search', [PermissionController::class, 'search'])->name('permissions.search');
        Route::resource('permissions', PermissionController::class);
    });


    Route::middleware((['permission:utilitarios.grupos']))->group(function () {
        Route::post('/roles/search', [RoleController::class, 'search'])->name('roles.search');
        Route::get('/roles/permissionForm/{id}', [RoleController::class, 'permissionForm'])->name('roles.permissionForm');
        Route::post('/roles/updatePermissions/{id}', [RoleController::class, 'updatePermissions'])->name('roles.updatePermissions');
        Route::resource('roles', RoleController::class);
    });

    // Route::middleware(['permission:utilitarios.usuarios'])->group(function () {
    Route::post('/users/search', [UserController::class, 'search'])->name('users.search');
    Route::resource('users', UserController::class);
    // });

    Route::post('/empresas/search', [EmpresaController::class, 'search'])->name('empresas.search');
    Route::get('/empresas/certificado/{id}', [EmpresaController::class, 'certificado'])->name('empresas.certificado');
    Route::put('/empresas/status/{id}', [EmpresaController::class, 'status'])->name('empresas.status');
    Route::resource('empresas', EmpresaController::class);
    
    Route::post('/acessos/search', [AcessoController::class, 'search'])->name('acessos.search');
    Route::put('/acessos/status/{id}', [AcessoController::class, 'status'])->name('acessos.status');
    Route::get('/acessos/opcoes/{id}', [AcessoController::class, 'opcoes'])->name('acessos.opcoes');
    Route::post('/acessos/acoes', [AcessoController::class, 'acoes'])->name('acessos.acoes');
    Route::resource('acessos', AcessoController::class);

    Route::post('/certificados/search', [CertificadoController::class, 'search'])->name('certificados.search');
    Route::get('/certificados/certificado/{chave}', [CertificadoController::class, 'certificado'])->name('certificados.certificado');
    Route::put('/certificados/status/{id}', [CertificadoController::class, 'status'])->name('certificados.status');
    Route::get('certificados/empresa/{cnpj?}', [CertificadoController::class, 'empresa'])->name('certificado.empresa');
    Route::post('certificados/sendMail', [CertificadoController::class, 'sendMail'])->name('certificado.sendMail');
    Route::get('certificados/createChave/{id?}', [CertificadoController::class, 'createChave'])->name('certificado.createChave');
    Route::resource('certificados', CertificadoController::class);
});
