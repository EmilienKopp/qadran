<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeDomainBoilerplate extends Command
{
    protected $signature = 'split:domain
                            {model : The name of the model}
                            {roles* : The roles to generate strategies for}';

    protected $description = 'Create a domain context and strategies for a model';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $model = $this->argument('model');
        $roles = $this->argument('roles');

        $pascalCaseDomainName = Str::studly($model);
        $lowerCaseDomainName = Str::lower($model);

        $domainPath = resource_path("js/Lib/domain/{$pascalCaseDomainName}");
        $strategiesPath = "{$domainPath}/strategies";

        // Create directories
        if (! $this->files->isDirectory($domainPath)) {
            $this->files->makeDirectory($domainPath, 0755, true);
            $this->info("Created directory: {$domainPath}");
        }
        if (! $this->files->isDirectory($strategiesPath)) {
            $this->files->makeDirectory($strategiesPath, 0755, true);
            $this->info("Created directory: {$strategiesPath}");
        }

        // Generate index.ts
        $indexContent = $this->generateIndexTsContent($pascalCaseDomainName);
        $this->files->put("{$domainPath}/index.ts", $indexContent);
        $this->info("Created: {$domainPath}/index.ts");

        // Generate context.ts
        $contextContent = $this->generateContextTsContent($pascalCaseDomainName, $roles);
        $this->files->put("{$domainPath}/context.ts", $contextContent);
        $this->info("Created: {$domainPath}/context.ts");

        // Generate strategies
        if (empty($roles)) {
            // Generate default strategy if no roles provided
            $defaultStrategyContent = $this->generateDefaultStrategyContent($pascalCaseDomainName);
            $this->files->put("{$strategiesPath}/default{$pascalCaseDomainName}TableStrategy.ts", $defaultStrategyContent);
            $this->info("Created: {$strategiesPath}/default{$pascalCaseDomainName}TableStrategy.ts");
        } else {
            // Generate role-specific strategies
            foreach ($roles as $role) {
                $roleStrategyContent = $this->generateRoleStrategyContent($pascalCaseDomainName, $role);
                $roleName = Str::camel($role);
                $this->files->put("{$strategiesPath}/{$roleName}{$pascalCaseDomainName}TableStrategy.ts", $roleStrategyContent);
                $this->info("Created: {$strategiesPath}/{$roleName}{$pascalCaseDomainName}TableStrategy.ts");
            }
        }

        $this->info("Domain context and strategies for '{$pascalCaseDomainName}' generated successfully!");
    }

    protected function generateIndexTsContent(string $pascalCaseDomainName): string
    {
        $lowerCaseDomainName = Str::lower($pascalCaseDomainName);

        return <<<TS
import { router } from "@inertiajs/svelte";
import { toaster } from "\$components/Feedback/Toast/ToastHandler.svelte";
import { {$pascalCaseDomainName}Base } from "\$lib/models/{$pascalCaseDomainName}";

export class {$pascalCaseDomainName} extends {$pascalCaseDomainName}Base {
  
  static delete({$lowerCaseDomainName}: {$pascalCaseDomainName}) {
    if(confirm('Are you sure you want to delete this {$lowerCaseDomainName}?')) {
      router.delete(route('{$lowerCaseDomainName}.destroy', {$lowerCaseDomainName}.id), {
        preserveScroll: true,
        onSuccess: () => {
          toaster.success('{$pascalCaseDomainName} deleted successfully');
        },
        onError: () => {
          toaster.error('Failed to delete {$lowerCaseDomainName}');
        }
      });
    }
  }
  
}
TS;
    }

    protected function generateContextTsContent(string $pascalCaseDomainName, array $roles): string
    {
        $imports = [];
        $cases = [];

        if (empty($roles)) {
            // Default strategy only
            $imports[] = "import { Default{$pascalCaseDomainName}TableStrategy } from './strategies/default{$pascalCaseDomainName}TableStrategy';";
            $cases[] = "      default:\n        return new Default{$pascalCaseDomainName}TableStrategy();";
        } else {
            // Role-specific strategies
            foreach ($roles as $role) {
                $roleName = Str::studly($role);
                $roleFileName = Str::camel($role);
                $imports[] = "import { {$roleName}{$pascalCaseDomainName}TableStrategy } from './strategies/{$roleFileName}{$pascalCaseDomainName}TableStrategy';";
                $cases[] = "      case '{$role}':\n        return new {$roleName}{$pascalCaseDomainName}TableStrategy();";
            }
            $cases[] = "      default:\n        return new ".Str::studly($roles[0])."{$pascalCaseDomainName}TableStrategy();";
        }

        $importsString = implode("\n", $imports);
        $casesString = implode("\n", $cases);

        return <<<TS
import { BaseDataDisplayStrategy } from '../../core/strategies/dataDisplayStrategy';
import { Context } from '\$lib/core/contexts/context';
{$importsString}
import { {$pascalCaseDomainName} } from '\$models';

export class {$pascalCaseDomainName}Context implements Context<{$pascalCaseDomainName}> {
  strategy: BaseDataDisplayStrategy<{$pascalCaseDomainName}>;

  constructor(role: string) {
    this.strategy = this.getStrategyForRole(role);
  }

  getStrategyForRole(role: string): BaseDataDisplayStrategy<{$pascalCaseDomainName}> {
    switch (role) {
{$casesString}
    }
  }
}
TS;
    }

    protected function generateRoleStrategyContent(string $pascalCaseDomainName, string $role): string
    {
        $lowerCaseDomainName = Str::lower($pascalCaseDomainName);
        $roleName = Str::studly($role);

        return <<<TS
import type { DataAction, DataHeader, IDataStrategy } from '\$types/common/dataDisplay';

import { BaseDataDisplayStrategy } from '\$lib/core/strategies/dataDisplayStrategy';
import { {$pascalCaseDomainName} } from '..';
import { date } from '\$lib/utils/formatting';

export class {$roleName}{$pascalCaseDomainName}TableStrategy
  extends BaseDataDisplayStrategy<{$pascalCaseDomainName}>
  implements IDataStrategy<{$pascalCaseDomainName}>
{
  defaultHeaders(): DataHeader<{$pascalCaseDomainName}>[] {
    return [
      { key: 'name', label: 'Name', searchable: true },
      { key: 'created_at', label: 'Created At', formatter: date },
      { key: 'updated_at', label: 'Updated At', formatter: date },
    ];
  }

  defaultActions(): DataAction<{$pascalCaseDomainName}>[] {
    return [
      { label: 'Edit', href: (row: {$pascalCaseDomainName}) => route('{$lowerCaseDomainName}.edit', row.id) },
      {
        label: 'Delete',
        callback: {$pascalCaseDomainName}.delete,
        css: () => 'text-red-500',
      },
    ];
  }
}
TS;
    }

    protected function generateDefaultStrategyContent(string $pascalCaseDomainName): string
    {
        $lowerCaseDomainName = Str::lower($pascalCaseDomainName);

        return <<<TS
import { DataAction, IDataStrategy } from '\$types/common/dataDisplay';

import { BaseDataDisplayStrategy } from '\$lib/core/strategies/dataDisplayStrategy';
import { {$pascalCaseDomainName} } from '..';
import { date } from '\$lib/utils/formatting';

export class Default{$pascalCaseDomainName}TableStrategy
  extends BaseDataDisplayStrategy<{$pascalCaseDomainName}>
  implements IDataStrategy<{$pascalCaseDomainName}>
{
  defaultHeaders() {
    return [
      { label: 'Name', key: 'name' },
      { label: 'Created At', key: 'created_at', formatter: date },
      { label: 'Updated At', key: 'updated_at', formatter: date },
    ];
  }

  defaultActions(): DataAction<{$pascalCaseDomainName}>[] {
    return [
      {
        label: 'View',
        href: (row: {$pascalCaseDomainName}) => route('{$lowerCaseDomainName}.show', row.id),
      },
      {
        label: 'Edit',
        href: (row: {$pascalCaseDomainName}) => route('{$lowerCaseDomainName}.edit', row.id),
      },
      {
        label: 'Delete',
        callback: {$pascalCaseDomainName}.delete,
        css: () => 'text-red-500',
      },
    ];
  }
}
TS;
    }
}
