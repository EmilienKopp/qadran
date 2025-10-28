<?php

namespace App\Support;

use App\Enums\ExecutionContext;

class RequestContextResolver
{
  public static function getExecutionContext(): ExecutionContext
  {
    if (php_sapi_name() === 'cli' || defined('LARAVEL_STARTING_IN_CONSOLE')) {
      return ExecutionContext::CLI;
    }

    if (config('nativephp-internal.running', false)) {
      return ExecutionContext::DESKTOP;
    }

    if (request()->isJson() || request()->wantsJson() || request()->header('Accept') === 'application/json') {
      return ExecutionContext::API;
    }

    return ExecutionContext::WEB;
  }

  public static function isDesktop(): bool
  {
    return self::getExecutionContext() === ExecutionContext::DESKTOP;
  }

  public static function getHost(): string
  {
    $host = request()->getHost();
    if (self::isDesktop()) {
      $host = 'localhost';
    }
    return $host;
  }

  public static function isDev(?string $host = null)
  {
    $host ??= RequestContextResolver::getHost();
    return !app()->isProduction() && ($host === 'localhost' || str($host)->contains('127.0.0.1'));
  }
}