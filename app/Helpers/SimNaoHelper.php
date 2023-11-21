<?php

namespace App\Helpers;

class SimNaoHelper
{
    /**
     * Campos listas estaticas
     * Contem os valores possiveis
     */
    public static $sim_nao = [
        'N' => 'NAO',
        'S' => 'SIM',
        '1' => 'SIM',
        '0' => 'NAO'
    ];

    public static function get($valor)
    {
        return isset($valor) ? self::$sim_nao[$valor] : '';
    }
}
