<?php

use App\Http\Controllers\Api\CertificadoController;
use Illuminate\Support\Facades\Route;

Route::post('certificado', [CertificadoController::class, 'index']);
Route::post('certificado/check', [CertificadoController::class, 'check']);
Route::post('certificado/uncheck', [CertificadoController::class, 'uncheck']);
Route::post('certificado/active', [CertificadoController::class, 'active']);
Route::post('certificado/statusUninstall', [CertificadoController::class, 'statusUninstall']);
Route::post('certificado/statusInactive', [CertificadoController::class, 'statusInactive']);
Route::post('certificado/statusActive', [CertificadoController::class, 'statusActive']);
Route::post('certificado/updateCertificate', [CertificadoController::class, 'updateCertificate']);
