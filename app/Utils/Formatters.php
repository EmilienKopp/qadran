<?php


namespace App\Utils;

use Illuminate\Support\Str;

class Formatters
{
    public static function toPGArrayString(array $array): string
    {
        return '{' . implode(',', $array) . '}';
    }

    public static function toPGArray(array $array): string
    {
      return json_encode($array);
    }
}