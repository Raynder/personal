<?php

declare(strict_types=1);

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AcessoController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\CertificadoraController;
use App\Http\Controllers\CnpjController;
use App\Http\Controllers\External\CustomerController;
use App\Http\Controllers\TreinoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NovidadeController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExercicioController;
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

    Route::middleware((['permission:utilitarios.treinos']))->group(function () {
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

    Route::prefix('/alunos')->group(function () {
        Route::post('search', [AlunoController::class, 'search'])->name('alunos.search');
        Route::get('/find', [AlunoController::class, 'find'])->name('alunos.find');
        Route::get('send-link-form', [AlunoController::class, 'sendLinkForm'])->name('alunos.sendLinkForm');
        Route::post('send-link', [AlunoController::class, 'sendLink'])->name('alunos.sendLink');
        Route::get('send-token-form/{id}', [AlunoController::class, 'sendTokenForm'])->name('alunos.sendTokenForm');
        Route::patch('send-token/{id}', [AlunoController::class, 'sendToken'])->name('alunos.sendToken');
        Route::get('certificado/{chave}', [AlunoController::class, 'certificado'])->name('alunos.certificado');
        Route::put('status/{id}', [AlunoController::class, 'status'])->name('alunos.status');
        Route::get('empresa/{cnpj?}', [AlunoController::class, 'empresa'])->name('alunos.empresa');
        Route::post('sendMail', [AlunoController::class, 'sendMail'])->name('alunos.sendMail');
        Route::post('sendTokensByMail', [AlunoController::class, 'sendTokensByMail'])->name('alunos.sendTokensByMail');
        Route::get('createChave/{id?}', [AlunoController::class, 'createChave'])->name('alunos.createChave');
        Route::post('compartilharEmLote', [AlunoController::class, 'compartilharEmLote'])->name('alunos.compartilharEmLote');
        Route::post('compartilharEmLoteForm', [AlunoController::class, 'compartilharEmLoteForm'])->name('alunos.compartilharEmLoteForm');
    });
    Route::resource('alunos', AlunoController::class);


    Route::post('/novidades/search', [NovidadeController::class, 'search'])->name('novidades.search');
    Route::resource('/novidades', NovidadeController::class);

    Route::post('/exercicios/search', [ExercicioController::class, 'search'])->name('exercicios.search');
    Route::get('/exercicios/find', [ExercicioController::class, 'find'])->name('exercicios.find');
    Route::resource('exercicios', ExercicioController::class);

    Route::post('/treinos/search', [TreinoController::class, 'search'])->name('treinos.search');
    Route::get('/treinos/find', [TreinoController::class, 'find'])->name('treinos.find');

    Route::resource('treinos', TreinoController::class);

    Route::get('/certificados/pdf/{id}', [AlunoController::class, 'pdf'])->name('certificados.pdf');
    Route::get('/relatorios/certificados', [RelatorioController::class, 'certificados'])->name('relatorios.certificados');
});
