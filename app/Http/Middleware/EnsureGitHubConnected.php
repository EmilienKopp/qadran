<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\GitHubAccountService;

class EnsureGitHubConnected
{
    public function handle(Request $request, Closure $next)
    {
        $githubService = new GitHubAccountService();
        
        if (!$githubService->hasValidConnection(auth()->user())) {
            return redirect()->route('settings.integrations')
                ->with('error', 'Please connect your GitHub account first.');
        }

        return $next($request);
    }
}