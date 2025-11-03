<?php

namespace App\Support;

use Illuminate\Support\Str;
use ReflectionClass;

class DataAccessDiscovery
{
    protected string $interfacePath = 'app/DataAccess';
    protected string $localNamespace = 'App\\DataAccess\\Local\\';
    protected string $remoteNamespace = 'App\\DataAccess\\Remote\\';
    protected string $interfaceNamespace = 'App\\DataAccess\\';

    /**
     * Discover all DataAccess interfaces and their implementations
     *
     * @return array Array of [interfaceFQCN => ['local' => localFQCN, 'remote' => remoteFQCN]]
     */
    public function discover(): array
    {
        $interfaces = $this->findDataAccessInterfaces();
        $discovered = [];

        foreach ($interfaces as $interface) {
            $resourceName = $this->extractResourceName($interface);

            $localClass = $this->localNamespace . $resourceName;
            $remoteClass = $this->remoteNamespace . $resourceName;

            $discovered[$interface] = [
                'local' => class_exists($localClass) ? $localClass : null,
                'remote' => class_exists($remoteClass) ? $remoteClass : null,
            ];
        }

        return $discovered;
    }

    /**
     * Find all *DataAccess interfaces
     */
    protected function findDataAccessInterfaces(): array
    {
        $path = base_path($this->interfacePath);
        $interfaces = [];

        if (!is_dir($path)) {
            return $interfaces;
        }

        $files = glob($path . '/*DataAccess.php');

        foreach ($files as $file) {
            $filename = basename($file, '.php');
            $interface = $this->interfaceNamespace . $filename;

            if (interface_exists($interface)) {
                $interfaces[] = $interface;
            }
        }

        return $interfaces;
    }

    /**
     * Extract resource name from interface
     * e.g., App\DataAccess\TenantDataAccess -> Tenant
     */
    protected function extractResourceName(string $interface): string
    {
        $className = class_basename($interface);
        return Str::before($className, 'DataAccess');
    }

    /**
     * Get all discovered bindings as a readable array
     */
    public function getBindings(): array
    {
        return array_filter(
            $this->discover(),
            fn($implementations) => $implementations['local'] !== null || $implementations['remote'] !== null
        );
    }
}
