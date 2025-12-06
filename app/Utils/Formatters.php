<?php

namespace App\Utils;

class Formatters
{
    public static function toPGArrayString(array $array): string
    {
        return '{'.implode(',', $array).'}';
    }

    public static function toPGArray(array $array): string
    {
        return json_encode($array);
    }

    public static function stripPlusAddressing(string $email): string
    {
        return preg_replace('/\+.*@/', '@', $email);
    }

    public static function isValidHost(string $host): bool
    {
        return preg_match('/^[a-z0-9-]+$/', $host) === 1;
    }
}
