<?php

namespace App\Helpers;

class TipoUsuarioHelper
{
    public static $options = [
        'F' => 'FUNCIONARIO',
        'C' => 'CLIENTE',
    ];

    public static function get($value)
    {
        return isset($value) ? self::$options[$value] : '';
    }
}
