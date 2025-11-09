<?php

namespace App\Utils;

use Illuminate\Support\Uri;

class UrlTools
{
  public static function getSubdomain(string $url)
  {
    $host = parse_url($url, PHP_URL_HOST);
    $parts = explode('.', $host);
    if (count($parts) > 2) {
      return implode('.', $parts)[0];
    }
    return null;
  }

}