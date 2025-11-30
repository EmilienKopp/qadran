<?php

namespace App\Console\Commands;

use App\DTOs\N8nConfig;
use App\Models\Landlord\Tenant;
use App\Services\AIService;
use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class SetUpTenantLevelN8nConfig extends Command
{
    private ?string $tenantHost;

    private ?N8nConfig $n8nConfig;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:n8n
        {--tenant= : The tenant subdomain to set up the n8n config for}
        {--json=: JSON string of n8n config values to set}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(public AIService $aiService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->tenantHost = $this->option('tenant');

        if (! $this->tenantHost) {
            return $this->interactive();
        }

        return $this->programmatic();
    }

    protected function programmatic()
    {
        $this->n8nConfig = $this->prepareConfig($this->option('json'));

        return $this->commit();
    }

    protected function interactive()
    {
        $this->tenantHost = text(
            label: 'Tenant Subdomain',
            placeholder: 'Enter the tenant subdomain (e.g., tenant1)',
        );

        $configJson = text(
            label: 'n8n Config JSON (optional)',
            placeholder: 'Enter the n8n config values as JSON',
        );

        $this->n8nConfig = $this->prepareConfig($configJson);

        return $this->commit(interactive: true);
    }

    protected function prepareConfig(?string $configJson): N8nConfig
    {
        $base = N8nConfig::fromArray([
            'active' => true,
            'mcp_endpoint_url' => $this->aiService->getMcpEndpointUrl($this->tenantHost),
        ]);
        $commandConfig = N8nConfig::fromJson(str($configJson)->trim()->squish());

        return $base->mergeWith($commandConfig);
    }

    protected function commit(bool $interactive = false): int
    {
        $tenant = Tenant::where('host', $this->tenantHost)->firstOrFail();
        $tenantConfig = $tenant->n8n_config ?? new N8nConfig;

        $this->info("Prepared n8n config for tenant '{$this->tenantHost}':");
        $tabulated = [];
        foreach ($this->n8nConfig->toArray() as $key => $value) {
            $tabulated[] = [
                'k' => $key,
                'v' => $value,
            ];
        }
        table(
            headers: ['Config Key', 'Value'],
            rows: $tabulated,
        );

        $OK = true;
        if ($interactive) {
            $OK = confirm('Apply the prepared n8n config to tenant '.$this->tenantHost.'?');
        }

        if ($OK) {
            $tenantConfig = $tenantConfig->mergeWith($this->n8nConfig);
            $tenant->n8n_config = $tenantConfig;
            $tenant->save();
            info('n8n config applied successfully.');

            return 0;
        }

        return 1;
    }
}
