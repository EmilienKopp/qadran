<?php

namespace App\Providers;

use App\Support\RequestContextResolver;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register repository bindings based on context (desktop vs web)
     */
    public function register(): void
    {
        // Register GuzzleHttp\Client as a singleton for remote repositories
        $this->app->singleton(\GuzzleHttp\Client::class, function () {
            return new \GuzzleHttp\Client;
        });

        $this->registerRepositories();
    }

    /**
     * Register repository bindings based on context (desktop vs web)
     */
    private function registerRepositories(): void
    {
        $isDesktop = RequestContextResolver::isDesktop();

        // Auto-discover repository interfaces and bind them
        $this->autoDiscoverRepositories($isDesktop);
    }

    /**
     * Auto-discover repository interfaces and bind them to appropriate implementations
     */
    private function autoDiscoverRepositories(bool $isDesktop): void
    {
        $repositoriesPath = app_path('Repositories');

        // Find all *RepositoryInterface.php files
        $interfaceFiles = glob($repositoriesPath.'/*RepositoryInterface.php');

        foreach ($interfaceFiles as $file) {
            $filename = basename($file, '.php');

            // Skip BaseRepositoryInterface as it's not meant to be bound
            if ($filename === 'BaseRepositoryInterface') {
                continue;
            }

            // Extract the repository name (e.g., "User" from "UserRepositoryInterface")
            $repositoryName = str_replace('RepositoryInterface', '', $filename);

            // Build the interface class name
            $interface = "App\\Repositories\\{$filename}";

            // Build the implementation class names
            $localImplementation = "App\\Repositories\\Local\\Local{$repositoryName}Repository";
            $remoteImplementation = "App\\Repositories\\Remote\\Remote{$repositoryName}Repository";

            // Verify classes exist before binding
            if (! interface_exists($interface)) {
                \Log::warning("Interface not found: {$interface}");

                continue;
            }

            if (! class_exists($localImplementation)) {
                \Log::warning("Local implementation not found: {$localImplementation}");

                continue;
            }

            if (! class_exists($remoteImplementation)) {
                \Log::warning("Remote implementation not found: {$remoteImplementation}");

                continue;
            }

            // Bind the interface to the appropriate implementation
            $this->app->bind(
                $interface,
                function ($app) use ($isDesktop, $localImplementation, $remoteImplementation) {
                    if ($isDesktop) {
                        return new $remoteImplementation(
                            $app->make(\GuzzleHttp\Client::class),
                            config('services.api.base_url')
                        );
                    }

                    return new $localImplementation;
                }
            );

        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bootstrap repository services
    }
}
