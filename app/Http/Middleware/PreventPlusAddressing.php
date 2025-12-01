<?php

namespace App\Http\Middleware;

class PreventPlusAddressing
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, \Closure $next)
  {
    if (!app()->isProduction()) {
      return $next($request);
    }
    $email = $request->input('email');

    if ($email && strpos($email, '+') !== false) {
      $cleanedEmail = preg_replace('/\+.*@/', '@', $email);
      $request->merge(['email' => $cleanedEmail]);
    }

    return $next($request);
  }
}