<?php

namespace App\Console\Commands;

use App\Support\DataAccessDiscovery;
use Illuminate\Console\Command;

class DataAccessListCommand extends Command
{
    protected $signature = 'data-access:list';

    protected $description = 'List all discovered DataAccess interfaces and their implementations';

    public function handle(): int
    {
        $discovery = new DataAccessDiscovery;
        $bindings = $discovery->getBindings();

        if (empty($bindings)) {
            $this->warn('No DataAccess interfaces found.');

            return self::SUCCESS;
        }

        $this->info('Discovered DataAccess Bindings:');
        $this->newLine();

        $tableData = [];
        foreach ($bindings as $interface => $implementations) {
            $tableData[] = [
                'Interface' => class_basename($interface),
                'FQCN' => $interface,
                'Local' => $implementations['local'] ? '✓ '.class_basename($implementations['local']) : '✗',
                'Remote' => $implementations['remote'] ? '✓ '.class_basename($implementations['remote']) : '✗',
            ];
        }

        $this->table(
            ['Interface', 'FQCN', 'Local', 'Remote'],
            $tableData
        );

        $this->newLine();
        $this->info('Current context: '.config('app.data_access_mode', 'local'));

        return self::SUCCESS;
    }
}
