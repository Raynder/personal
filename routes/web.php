<?php

declare(strict_types=1);

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AcessoController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\CertificadoraController;
use App\Http\Controllers\CnpjController;
use App\Http\Controllers\External\CustomerController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NovidadeController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return view('auth.login');
});

Route::prefix('external')->group(function () {
    Route::get('/customer', [CustomerController::class, 'index'])->name('external.customer.index');
    Route::get('/customer/create', [CustomerController::class, 'create'])->name('external.customer.create');
    Route::post('/customer/store', [CustomerController::class, 'store'])->name('external.customer.store');
    Route::get('/customer/success', [CustomerController::class, 'success'])->name('external.customer.success');
    Route::get('/customer/error', [CustomerController::class, 'error'])->name('external.customer.error');
});


Route::middleware(['auth'])->group(function () {
    Route::post('/dashboard/search', [DashboardController::class, 'search'])->name('dashboard.search');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/cnpj', [CnpjController::class, 'index'])->name('search.cnpj');

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
    Route::post('/certificadoras/search', [CertificadoraController::class, 'search'])->name('certificadoras.search');
    Route::resource('certificadoras', CertificadoraController::class);

    Route::post('/empresas/search', [EmpresaController::class, 'search'])->name('empresas.search');
    Route::get('/empresas/certificado/{id}', [EmpresaController::class, 'certificado'])->name('empresas.certificado');
    Route::put('/empresas/status/{id}', [EmpresaController::class, 'status'])->name('empresas.status');
    Route::resource('empresas', EmpresaController::class);

    Route::post('/acessos/search', [AcessoController::class, 'search'])->name('acessos.search');
    Route::put('/acessos/status/{id}', [AcessoController::class, 'status'])->name('acessos.status');
    Route::get('/acessos/opcoes/{id}', [AcessoController::class, 'opcoes'])->name('acessos.opcoes');
    Route::post('/acessos/acoes', [AcessoController::class, 'acoes'])->name('acessos.acoes');
    Route::resource('acessos', AcessoController::class);

    Route::prefix('/certificados')->group(function () {
        Route::post('search', [CertificadoController::class, 'search'])->name('certificados.search');
        Route::get('/find', [CertificadoController::class, 'find'])->name('certificados.find');
        Route::get('send-link-form', [CertificadoController::class, 'sendLinkForm'])->name('certificados.sendLinkForm');
        Route::post('send-link', [CertificadoController::class, 'sendLink'])->name('certificados.sendLink');
        Route::get('send-token-form/{id}', [CertificadoController::class, 'sendTokenForm'])->name('certificados.sendTokenForm');
        Route::patch('send-token/{id}', [CertificadoController::class, 'sendToken'])->name('certificados.sendToken');
        Route::get('certificado/{chave}', [CertificadoController::class, 'certificado'])->name('certificados.certificado');
        Route::put('status/{id}', [CertificadoController::class, 'status'])->name('certificados.status');
        Route::get('empresa/{cnpj?}', [CertificadoController::class, 'empresa'])->name('certificado.empresa');
        Route::post('sendMail', [CertificadoController::class, 'sendMail'])->name('certificado.sendMail');
        Route::post('sendTokensByMail', [CertificadoController::class, 'sendTokensByMail'])->name('certificado.sendTokensByMail');
        Route::get('createChave/{id?}', [CertificadoController::class, 'createChave'])->name('certificado.createChave');
        Route::post('compartilharEmLote', [CertificadoController::class, 'compartilharEmLote'])->name('certificados.compartilharEmLote');
        Route::post('compartilharEmLoteForm', [CertificadoController::class, 'compartilharEmLoteForm'])->name('certificados.compartilharEmLoteForm');
    });
    Route::resource('certificados', CertificadoController::class);


    Route::post('/novidades/search', [NovidadeController::class, 'search'])->name('novidades.search');
    Route::resource('/novidades', NovidadeController::class);

    Route::post('/usuarios/search', [UsuarioController::class, 'search'])->name('usuarios.search');
    Route::get('/usuarios/find', [UsuarioController::class, 'find'])->name('usuarios.find');
    Route::resource('usuarios', UsuarioController::class);

    Route::post('/grupos/search', [GrupoController::class, 'search'])->name('grupos.search');
    Route::get('/grupos/find', [GrupoController::class, 'find'])->name('grupos.find');

    Route::resource('grupos', GrupoController::class);

    Route::get('/certificados/pdf/{id}', [CertificadoController::class, 'pdf'])->name('certificados.pdf');
    Route::get('/relatorios/certificados', [RelatorioController::class, 'certificados'])->name('relatorios.certificados');
});
