<?php

namespace App\Helpers;

class RegimeTributarioHelper
{
    public static $regimes = [
        'S' => 'SIMPLES',
        'R' => 'REAL',
        'P' => 'PRESUMIDO',
    ];

    public static function get($valor)
    {
        return isset($valor) ? self::$regimes[$valor] : '';
    }
}
