<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Get the account parameter from the route if it exists
                $account = $request->route('account');
                
                // Build the dashboard route with account parameter if needed
                if ($account) {
                    return redirect()->route('dashboard', ['account' => $account]);
                }
                
                // Otherwise, redirect without the account parameter (local dev)
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
