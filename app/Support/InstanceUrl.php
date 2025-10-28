<?php


namespace App\Support;

class InstanceUrl
{
  public static function fromSettings(): string|null
  {
    return \Native\Desktop\Facades\Settings::get('instance_url');
  }

  public static function build(string $host): string
  {
    if(RequestContextResolver::isDev($host)) {
      return 'http://localhost:8000/';
    }
    $scheme = parse_url(config('app.url'), PHP_URL_SCHEME);
    return "{$scheme}://{$host}/";
  }
}