<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Inertia\Response as InertiaResponse;

abstract class HybridController extends BaseController
{
  use AuthorizesRequests, ValidatesRequests;

  /**
   * Respond with data - JSON for API, Inertia for web/desktop
   */
  protected function respond(mixed $data, callable $inertiaRenderer): JsonResponse|InertiaResponse
  {
    if ($this->isApiRequest()) {
      return response()->json($data);
    }

    return $inertiaRenderer($data);
  }

  /**
   * Determine if this is an API request
   */
  protected function isApiRequest(): bool
  {
    return request()->is('api/*') ||
      request()->expectsJson() ||
      request()->header('X-Api-Request') === 'true';
  }
}