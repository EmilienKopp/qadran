<?php

namespace App\Http\Middleware;

use App\Enums\ExecutionContext;
use App\Models\User;
use App\Support\RequestContextResolver;
use Laravel\Passport\ClientRepository;

/**
 * Will ultimately be removed and part of User onboarding process!
 */
class EnsureCLIOAuthClient
{
    public function __construct(public ClientRepository $clientRepository)
    {
    }

    /**
     * Handle an incoming request.
     */
    public function handle($request, \Closure $next)
    {
        $user = $request->user();
        if (!$user instanceof User) {
            return $next($request);

        }

        $clientName = 'Qadran CLI';

        $clients = $this->clientRepository->forUser($user)->where('name', $clientName);

        if ($clients->isEmpty()) {
            $this->clientRepository->createAuthorizationCodeGrantClient(
                user: $user instanceof User ? $user : null,
                name: $clientName,
                redirectUris: ['http://localhost:8765/callback'],
                confidential: false,
                enableDeviceFlow: true
            );
        }

        return $next($request);
    }
}