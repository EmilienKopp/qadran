<?php

namespace App\Http\Middleware;

use App\Services\GitHubAccountService;
use Closure;
use Illuminate\Http\Request;

class EnsureGitHubConnected
{
    public function handle(Request $request, Closure $next)
    {
        $githubService = new GitHubAccountService;

        if (! $githubService->hasValidConnection(auth()->user())) {
            return redirect()->route('settings.integrations')
                ->with('error', 'Please connect your GitHub account first.');
        }

        return $next($request);
    }
}
