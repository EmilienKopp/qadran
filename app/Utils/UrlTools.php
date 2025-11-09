<?php

namespace App\Utils;

class UrlTools
{
    public static function getSubdomain(string $url)
    {
        $host = parse_url($url, PHP_URL_HOST) ?? $url;
        $parts = explode('.', $host);
        if (count($parts) >= 3) {
            return $parts[0];
        }

        return null;
    }
}
