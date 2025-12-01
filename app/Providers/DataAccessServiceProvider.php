<?php

namespace App\Providers;

use App\Enums\ExecutionContext;
use App\Support\DataAccessDiscovery;
use App\Support\RequestContextResolver;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class DataAccessServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerDataAccessDiscovery();
    }

    /**
     * Auto-discover and register all DataAccess interfaces
     */
    protected function registerDataAccessDiscovery(): void
    {
        $discovery = new DataAccessDiscovery;
        $bindings = $discovery->getBindings();

        foreach ($bindings as $interface => $implementations) {
            $this->app->bind($interface, function ($app) use ($implementations) {
                return $this->resolveImplementation($app, $implementations);
            });
        }
    }

    /**
     * Resolve the appropriate implementation based on context
     */
    protected function resolveImplementation($app, array $implementations)
    {
        return match ($this->determineContext()) {
            ExecutionContext::DESKTOP => $this->createRemoteImplementation($app, $implementations['remote']),
            default => $this->createLocalImplementation($implementations['local']),
        };
    }

    /**
     * Determine the current execution context
     */
    protected function determineContext(): ExecutionContext
    {
        return RequestContextResolver::getExecutionContext();
    }

    /**
     * Create a local (database) implementation
     */
    protected function createLocalImplementation(?string $class)
    {
        if (! $class) {
            throw new \RuntimeException('Local implementation not found');
        }

        return new $class;
    }

    /**
     * Create a remote (API) implementation
     * -> instantiate with Guzzle client and base URL
     */
    protected function createRemoteImplementation($app, ?string $class)
    {
        if (! $class) {
            throw new \RuntimeException('Remote implementation not found');
        }

        return new $class(
            $app->make(Client::class),
            config('services.api.base_url', 'http://localhost:8000')
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
