<?php

namespace App\Console\Commands;

use App\Models\Landlord\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class RegenerateViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:regen {name? : The name of the view to regenerate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate database views from SQL files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $viewName = $this->argument('name');
        $viewsPath = database_path('views');

        // Get all active tenants
        $tenants = Tenant::all();

        if ($tenants->isEmpty()) {
            $this->warn('No active tenants found.');

            return;
        }

        $this->info("Found {$tenants->count()} active tenants.");
        $bar = $this->output->createProgressBar($tenants->count());
        $bar->start();

        $successCount = 0;
        $failedTenants = [];

        foreach ($tenants as $tenant) {
            try {
                // Switch to tenant's database
                $tenant->makeCurrent();

                // Configure database connection
                config(['database.default' => 'tenant']);
                DB::purge('tenant');
                DB::reconnect('tenant');

                if ($viewName) {
                    $this->regenerateView($viewName, $viewsPath);
                } else {
                    $this->regenerateAllViews($viewsPath);
                }

                $successCount++;

            } catch (\Exception $e) {
                Log::error('View regeneration failed for tenant', [
                    'tenant' => $tenant->name,
                    'error' => $e->getMessage(),
                ]);

                $failedTenants[] = [
                    'name' => $tenant->name,
                    'error' => $e->getMessage(),
                ];

            } finally {
                // Always clear tenant and advance progress
                Tenant::forgetCurrent();
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('View regeneration completed:');
        $this->info("✓ Successfully processed: {$successCount} tenants");

        if (! empty($failedTenants)) {
            $this->error('✗ Failed tenants: '.count($failedTenants));
            foreach ($failedTenants as $failed) {
                $this->error("  - {$failed['name']}: {$failed['error']}");
            }
        }
    }

    /**
     * Regenerate a specific view.
     */
    protected function regenerateView(string $viewName, string $viewsPath): void
    {
        $sqlFile = $viewsPath.'/'.$viewName.'.sql';

        if (! File::exists($sqlFile)) {
            $this->error("View file not found: {$sqlFile}");

            return;
        }

        try {
            $sql = File::get($sqlFile);

            // Try both naming patterns
            $viewNames = [
                $viewName,                    // e.g. "runners"
                $viewName.'_view',           // e.g. "runners_view"
            ];

            $success = false;
            $lastError = null;

            foreach ($viewNames as $vName) {
                try {
                    // Drop existing view if it exists
                    DB::unprepared("DROP VIEW IF EXISTS {$vName} CASCADE");

                    // Create the view
                    DB::unprepared($sql);
                    $this->info("Successfully regenerated view: {$vName}");
                    $success = true;
                    break; // Exit loop on first success

                } catch (\Exception $e) {
                    $lastError = $e;
                    // Continue to try next naming pattern
                }
            }

            if (! $success) {
                throw new \Exception(
                    'Failed to create view with either naming pattern. Last error: '.
                    $lastError->getMessage()
                );
            }

        } catch (\Exception $e) {
            $this->error("Failed to regenerate view {$viewName}: ".$e->getMessage());
        }
    }

    /**
     * Regenerate all views in the views directory.
     */
    protected function regenerateAllViews(string $viewsPath): void
    {
        if (! File::exists($viewsPath)) {
            $this->error("Views directory not found: {$viewsPath}");

            return;
        }

        $files = File::files($viewsPath);
        $successCount = 0;
        $failCount = 0;

        foreach ($files as $file) {
            if ($file->getExtension() !== 'sql') {
                continue;
            }

            $viewName = $file->getBasename('.sql');

            try {
                $sql = File::get($file->getPathname());

                // Try both naming patterns
                $viewNames = [
                    $viewName,                    // e.g. "runners"
                    $viewName.'_view',           // e.g. "runners_view"
                ];

                $success = false;
                $lastError = null;

                foreach ($viewNames as $vName) {
                    try {
                        // Drop existing view if it exists
                        DB::unprepared("DROP VIEW IF EXISTS {$vName} CASCADE");

                        // Create the view
                        DB::unprepared($sql);
                        $this->info("Successfully regenerated view: {$vName}");
                        $success = true;
                        break; // Exit loop on first success

                    } catch (\Exception $e) {
                        $lastError = $e;
                        // Continue to try next naming pattern
                    }
                }

                if ($success) {
                    $successCount++;
                } else {
                    throw new \Exception(
                        'Failed to create view with either naming pattern. Last error: '.
                        $lastError->getMessage()
                    );
                }

            } catch (\Exception $e) {
                $this->error("Failed to regenerate view {$viewName}: ".$e->getMessage());
                $failCount++;
            }
        }

        $this->info("\nRegeneration complete:");
        $this->info("Successfully regenerated: {$successCount} views");
        if ($failCount > 0) {
            $this->error("Failed to regenerate: {$failCount} views");
        }
    }
}
