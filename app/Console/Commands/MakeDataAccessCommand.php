<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeDataAccessCommand extends Command
{
    protected $signature = 'make:data-access
                            {model : The model class name (e.g., Tenant, Project)}
                            {--endpoint= : API endpoint for remote access (default: api/{plural})}
                            {--no-facade : Skip creating a facade}
                            {--force : Overwrite existing files}';

    protected $description = 'Generate DataAccess interface, Local/Remote implementations, and Facade';

    protected string $modelName;
    protected string $dataAccessInterface;
    protected string $endpoint;

    public function handle(): int
    {
        $this->modelName = $this->argument('model');
        $this->dataAccessInterface = "{$this->modelName}DataAccess";
        $this->endpoint = $this->option('endpoint') ?? 'api/' . Str::plural(Str::lower($this->modelName));

        $this->info("Generating DataAccess for: {$this->modelName}");
        $this->newLine();

        // Create files
        $this->createInterface();
        $this->createLocalImplementation();
        $this->createRemoteImplementation();

        if (!$this->option('no-facade')) {
            $this->createFacade();
        }

        $this->newLine();
        $this->info('✓ DataAccess generation complete!');
        $this->newLine();

        $this->comment('Usage example:');
        if (!$this->option('no-facade')) {
            $this->line("  \\App\\DataAccess\\Facades\\{$this->modelName}::find(1);");
        } else {
            $this->line("  app(\\App\\DataAccess\\{$this->dataAccessInterface}::class)->find(1);");
        }

        return self::SUCCESS;
    }

    protected function createInterface(): void
    {
        $path = app_path("DataAccess/{$this->dataAccessInterface}.php");

        if (file_exists($path) && !$this->option('force')) {
            $this->warn("Interface already exists: {$this->dataAccessInterface}");
            return;
        }

        $content = $this->getInterfaceStub();
        $this->ensureDirectoryExists(dirname($path));
        file_put_contents($path, $content);

        $this->info("✓ Created interface: app/DataAccess/{$this->dataAccessInterface}.php");
    }

    protected function createLocalImplementation(): void
    {
        $path = app_path("DataAccess/Local/{$this->modelName}.php");

        if (file_exists($path) && !$this->option('force')) {
            $this->warn("Local implementation already exists: Local\\{$this->modelName}");
            return;
        }

        $content = $this->getLocalStub();
        $this->ensureDirectoryExists(dirname($path));
        file_put_contents($path, $content);

        $this->info("✓ Created local implementation: app/DataAccess/Local/{$this->modelName}.php");
    }

    protected function createRemoteImplementation(): void
    {
        $path = app_path("DataAccess/Remote/{$this->modelName}.php");

        if (file_exists($path) && !$this->option('force')) {
            $this->warn("Remote implementation already exists: Remote\\{$this->modelName}");
            return;
        }

        $content = $this->getRemoteStub();
        $this->ensureDirectoryExists(dirname($path));
        file_put_contents($path, $content);

        $this->info("✓ Created remote implementation: app/DataAccess/Remote/{$this->modelName}.php");
    }

    protected function createFacade(): void
    {
        $path = app_path("DataAccess/Facades/{$this->modelName}.php");

        if (file_exists($path) && !$this->option('force')) {
            $this->warn("Facade already exists: Facades\\{$this->modelName}");
            return;
        }

        $content = $this->getFacadeStub();
        $this->ensureDirectoryExists(dirname($path));
        file_put_contents($path, $content);

        $this->info("✓ Created facade: app/DataAccess/Facades/{$this->modelName}.php");
    }

    protected function ensureDirectoryExists(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    protected function getInterfaceStub(): string
    {
        return <<<PHP
<?php

namespace App\DataAccess;

use App\Contracts\BaseDataAccess;

interface {$this->dataAccessInterface} extends BaseDataAccess
{
    // Add custom methods here
}

PHP;
    }

    protected function getLocalStub(): string
    {
        $modelClass = $this->guessModelClass();
        $modelAlias = "{$this->modelName}Model";
        $useStatement = $modelClass ? "use {$modelClass} as {$modelAlias};" : "// use App\\Models\\{$this->modelName} as {$modelAlias};";
        $modelProperty = $modelClass ? class_basename($modelClass) . '::class' : "{$modelAlias}::class";

        return <<<PHP
<?php

namespace App\DataAccess\Local;

use App\DataAccess\{$this->dataAccessInterface};
{$useStatement}

class {$this->modelName} extends BaseLocalAccess implements {$this->dataAccessInterface}
{
    protected string \$model = {$modelProperty};
}

PHP;
    }

    protected function getRemoteStub(): string
    {
        return <<<PHP
<?php

namespace App\DataAccess\Remote;

use App\DataAccess\{$this->dataAccessInterface};

class {$this->modelName} extends BaseRemoteAccess implements {$this->dataAccessInterface}
{
    protected string \$resourceEndpoint = '{$this->endpoint}';
}

PHP;
    }

    protected function getFacadeStub(): string
    {
        return <<<PHP
<?php

namespace App\DataAccess\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed find(\$id)
 * @method static mixed firstWhere(\$column, \$operator, \$value = null)
 *
 * @see \App\DataAccess\{$this->dataAccessInterface}
 */
class {$this->modelName} extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\DataAccess\\{$this->dataAccessInterface}::class;
    }
}

PHP;
    }

    protected function guessModelClass(): ?string
    {
        // Try common model locations
        $possibilities = [
            "App\\Models\\{$this->modelName}",
            "App\\Models\\Landlord\\{$this->modelName}",
            "App\\Models\\Tenant\\{$this->modelName}",
        ];

        foreach ($possibilities as $class) {
            if (class_exists($class)) {
                return $class;
            }
        }

        return null;
    }
}