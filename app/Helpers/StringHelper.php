<?php

namespace App\Helpers;

class StringHelper
{
    public static function retirarAcentos($value)
    {
        return preg_replace(
            ["/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç)/", "/(Ç)/"],
            explode(" ", "a A e E i I o O u U n N c C"),
            $value
        );
    }

    public static function completeWithZeros($value, $size)
    {
        return str_pad($value, $size, '0', STR_PAD_LEFT);
    }

    public static function formatCnpjCpf($value)
    {
        $CPF_LENGTH = 11;
        $cnpj_cpf = FormatterHelper::onlyNumbers($value); //preg_replace("/\D/", '', $value);

        if (strlen($cnpj_cpf) === $CPF_LENGTH) {
            $cnpj_cpf = FormatterHelper::completeWithZeros($value, 11);
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
        }

        $cnpj_cpf = FormatterHelper::completeWithZeros($value, 14);
        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }

    public static function formatFone($value)
    {
        $FONE_LENGTH = 11;
        $fone = FormatterHelper::onlyNumbers($value); //preg_replace("/\D/", '', $value);

        if (strlen($fone) === $FONE_LENGTH) {
            return preg_replace("/(\d{2})(\d{5})(\d{4})/", "(\$1) \$2-\$3", $fone);
        }

        return preg_replace("/(\d{2})(\d{4})(\d{4})/", "(\$1) \$2-\$3", $fone);
    }

    public static function brToUS($value)
    {
        $br = ['.', ',']; // substitui esse
        $us = ['', '.'];  // por esse
        return str_replace($br, $us, $value);
    }

    public static function validateCpf($value)
    {
        if (strlen($value) > 11) {
            return false;
        }
        $cpf = FormatterHelper::onlyNumbers($value);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    public static function retirarCaracteresEspeciais($value)
    {
        return preg_replace('/[^a-zA-Z0-9]/', '', $value);
    }

    public static function contains($value, $search)
    {
        return strpos($value, $search) !== false;
    }
}
