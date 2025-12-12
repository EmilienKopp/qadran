<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class CreateNewTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:tenant
                                {--name= : The name of the tenant}
                                {--domain= : The domain of the tenant}
                                {--host= : The host of the tenant}
                                ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected ?string $tenantName;

    protected ?string $domain;

    protected ?string $host;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->tenantName = $this->getTenantNameOption();
        $this->domain = $this->getDomainOption();
        $this->host = $this->getHostOption();

        // Confirmation loop
        $confirmed = false;
        while (! $confirmed) {
            $this->newLine();
            $this->info('Please review the tenant information:');
            $this->newLine();

            table(
                ['Field', 'Value'],
                [
                    ['Tenant Name', $this->tenantName],
                    ['Domain', $this->domain],
                    ['Host', $this->host],
                ]
            );

            $this->newLine();

            if (confirm('Is all the information correct?', default: true)) {
                $confirmed = true;
            } else {
                $field = select(
                    label: 'Which field would you like to modify?',
                    options: [
                        'name' => 'Tenant Name',
                        'domain' => 'Domain',
                        'host' => 'Host',
                    ],
                );

                match ($field) {
                    'name' => $this->tenantName = $this->getTenantNameOption(skipOption: true),
                    'domain' => $this->domain = $this->getDomainOption(skipOption: true),
                    'host' => $this->host = $this->getHostOption(skipOption: true),
                };
            }
        }
        try {
            $this->duplicateTenantDatabase();
        } catch (\Exception $e) {
            $this->error('Failed to create tenant database: ' . $e->getMessage());
            return;
        }

        $this->newLine();
        $this->info('Tenant created successfully!');

    }

    private function createWorkOSOrg(): void
    {
        // Placeholder for WorkOS tenant creation logic
    }

    private function getTenantNameOption(bool $skipOption = false): string
    {
        $option = null;

        if (! $skipOption) {
            $option = $this->option('name');
        }

        if (! $option) {
            $option = text(
                label: 'Please enter the tenant name: ',
                placeholder: 'e.g. Acme Corporation',
                hint: 'The official name of you or your organization.'
            );
        }

        return str($option)->trim()->squish()->toString();
    }

    private function getDomainOption(bool $skipOption = false): string
    {
        $option = null;

        if (! $skipOption) {
            $option = $this->option('domain');
        }

        while (! $option || ! $this->isValidDomain($option)) {
            if ($option && ! $this->isValidDomain($option)) {
                $this->error('Invalid domain format. Please enter a valid domain (e.g., example.com).');
            }

            $option = text(
                label: 'Please enter the tenant domain: ',
                placeholder: 'e.g. acme-corp.com',
                hint: 'The primary domain for your organization.'
            );

            $option = str($option)->trim()->toString();
        }

        return $option;
    }

    private function isValidDomain(string $domain): bool
    {
        // Basic domain validation pattern
        $pattern = '/^(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,}$/';

        return preg_match($pattern, $domain) === 1;
    }

    private function getHostOption(bool $skipOption = false): string
    {
        $option = null;

        if (! $skipOption) {
            $option = $this->option('host');
        }

        while (! $option || ! $this->isValidHost($option)) {
            if ($option && ! $this->isValidHost($option)) {
                $this->error('The host can only contain letters, numbers, and hyphens. Please try again.');
            }

            $option = text(
                label: 'Please enter the tenant host: ',
                placeholder: 'e.g. acme',
                hint: 'The subdomain or host identifier for your organization.'
            );

            $option = str($option)->trim()->toString();
        }

        return $option;
    }

    private function duplicateTenantDatabase(): void
    {
        DB::connection('landlord')->unprepared(<<<SQL
            CREATE DATABASE {$this->host}_db TEMPLATE tenant_template;
        SQL);
    }

    private function isValidHost(string $host): bool
    {
        return preg_match('/^[a-zA-Z0-9-]+$/', $host) === 1;
    }
}
