<?php

use App\Http\Controllers\Api\CertificadoController;
use App\Http\Controllers\Api\NovidadeController;
use App\Http\Controllers\Api\ExercicioController;
use Illuminate\Support\Facades\Route;

Route::post('/certificado', [CertificadoController::class, 'download']);
Route::post('/certificado/retry', [CertificadoController::class, 'retry']);
Route::post('/certificado/check', [CertificadoController::class, 'check']);
Route::post('/certificado/active', [CertificadoController::class, 'active']);
Route::post('/certificado/uncheck', [CertificadoController::class, 'uncheck']);
Route::post('/certificado/statusUninstall', [CertificadoController::class, 'statusUninstall']);
Route::post('/certificado/statusInactive', [CertificadoController::class, 'statusInactive']);
Route::post('/certificado/certificadosValidos', [CertificadoController::class, 'certificadosValidos']);
Route::post('/certificado/certificadosInalidos', [CertificadoController::class, 'certificadosInalidos']);
Route::post('/certificado/statusActive', [CertificadoController::class, 'statusActive']);
Route::post('/certificado/updateCertificate', [CertificadoController::class, 'updateCertificate']);
Route::get('/certificado/getAllCertificates', [CertificadoController::class, 'getAllCertificates']);

Route::post('/novidades/verify', [NovidadeController::class, 'verify']);

Route::post('/usuarios/create', [ExercicioController::class, 'create']);