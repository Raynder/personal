<?php

namespace App\Helpers;

class StatusAcessoHelper
{
    public static $status = [
        'A' => 'ATIVO',
        'AA' => 'ATIVO - BYTOKEN',
        'AE' => 'ATIVO - EXTERNO',
        'I' => 'INATIVO',
        'P' => 'SEM UTILIZAÇÃO',
        'D' => 'DESINSTALADO',
        'PD' => 'PENDENTE(desinstalação)',
        'PA' => 'PENDENTE(aprovacao)',
        'PI' => 'PENDENTE(inativacao)',
    ];

    public static $icones = [
        'A' => 'fas fa-check-circle text-success',
        'AA' => 'fas fa-check-circle text-warning',
        'AE' => 'fas fa-check-circle text-danger',
        'I' => 'fas fa-times-circle text-danger',
        'P' => 'fas fa-exclamation-triangle text-warning',
        'D' => 'fas fa-exclamation-triangle text-black',
        'PD' => 'fas fa-hourglass-start text-black',
        'PA' => 'fas fa-hourglass-start text-success',
        'PI' => 'fas fa-hourglass-start text-danger',
    ];

    public static function getStatus($valor)
    {
        return self::$status[$valor];
    }

    public static function getIcone($valor)
    {
        return self::$icones[$valor];
    }

    public static function getAllStatus()
    {
        return self::$status;
    }
}
