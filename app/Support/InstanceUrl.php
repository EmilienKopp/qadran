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
    if (RequestContextResolver::isDev($host) || app()->isLocal()) {
      return 'http://localhost:8000';
    }
    $scheme = parse_url(config('app.url'), PHP_URL_SCHEME);
    return "{$scheme}://{$host}";
  }

  public static function fetch(string $host): string|null
  {
    $client = new \GuzzleHttp\Client();
    $url = config('services.api.base_url', 'http://localhost:8000');
    $response = $client->get($url . '/api/instance-url/' . $host);
    if ($response->getStatusCode() === 200) {
      $data = json_decode($response->getBody()->getContents(), true);
      return $data['instance_url'] ?? null;
    }
    return null;
  }
}