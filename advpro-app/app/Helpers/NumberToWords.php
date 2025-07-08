<?php

namespace App\Helpers;

class NumberToWords
{
    private static $unidades = [
        '', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve'
    ];

    private static $decenas = [
        '', 'diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'
    ];

    private static $centenas = [
        '', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'
    ];

    private static $especiales = [
        11 => 'once', 12 => 'doce', 13 => 'trece', 14 => 'catorce', 15 => 'quince',
        16 => 'dieciséis', 17 => 'diecisiete', 18 => 'dieciocho', 19 => 'diecinueve',
        21 => 'veintiuno', 22 => 'veintidós', 23 => 'veintitrés', 24 => 'veinticuatro',
        25 => 'veinticinco', 26 => 'veintiséis', 27 => 'veintisiete', 28 => 'veintiocho', 29 => 'veintinueve'
    ];

    public static function convert($number)
    {
        $part_entera = floor($number);
        $part_decimal = round(($number - $part_entera) * 100);

        $letras_enteras = self::convertirParteEntera($part_entera);
        
        $resultado = $letras_enteras;
        if ($part_decimal > 0) {
            $resultado .= ' con ' . $part_decimal . '/100';
        }

        return trim(strtoupper($resultado));
    }
    
    private static function convertirParteEntera($n)
    {
        if ($n < 0 || $n > 999999999) {
            return 'Número fuera de rango';
        }

        if ($n == 0) {
            return 'cero';
        }

        $chunks = array_reverse(str_split(str_pad($n, 9, '0', STR_PAD_LEFT), 3));
        $words = [];

        foreach ($chunks as $i => $chunk) {
            if ((int)$chunk > 0) {
                $segment = self::convertirChunk($chunk);
                if ($i == 1) { // Miles
                    $words[] = $segment . ($segment == 'uno' ? ' mil' : ' mil');
                } elseif ($i == 2) { // Millones
                    $words[] = $segment . ($segment == 'uno' ? ' millón' : ' millones');
                } else {
                    $words[] = $segment;
                }
            }
        }

        return implode(' ', array_reverse($words));
    }

    private static function convertirChunk($n_str)
    {
        $n = (int)$n_str;
        if ($n == 100) {
            return 'cien';
        }

        $output = '';
        $c = (int)$n_str[0];
        $d = (int)$n_str[1];
        $u = (int)$n_str[2];
        
        if ($c > 0) {
            $output .= self::$centenas[$c] . ' ';
        }
        
        $decenas_unidades = $d * 10 + $u;
        
        if ($decenas_unidades > 0) {
            if ($decenas_unidades > 10 && $decenas_unidades < 20 || in_array($decenas_unidades, array_keys(self::$especiales))) {
                if(isset(self::$especiales[$decenas_unidades])) {
                    $output .= self::$especiales[$decenas_unidades];
                } else {
                     $output .= self::$decenas[$d];
                }
            } else {
                $output .= self::$decenas[$d];
                if ($u > 0) {
                    $output .= ' y ' . self::$unidades[$u];
                }
            }
        }
        
        return rtrim($output);
    }
}