<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use function Laravel\Prompts\multiselect;

class ModelGen extends Command
{
    protected $signature = 'split:modelgen {--types-only : Generate only TypeScript interfaces}';

    protected $description = 'Generate TypeScript models and interfaces from Laravel models';

    protected $outputDir;

    protected $typesOutputPath;

    protected $models;

    protected Collection $selectedModels;

    private $connection = 'tenant';

    private $driver;

    private $database = 'qadran_db';

    public function __construct()
    {
        parent::__construct();
        $this->outputDir = base_path('resources/js/Lib/models/');
        $this->typesOutputPath = config('typegen.output', base_path('resources/js/types/models.d.ts'));
    }

    public function handle()
    {
        if (! $this->validateEnvironment()) {
            return;
        }

        $this->setupTenantConnection();
        $this->loadModels();

        if ($this->shouldUseAllModels()) {
            $this->selectedModels = $this->models;
        } else {
            $this->selectedModels = $this->getSelectedModels();
        }

        if ($this->selectedModels->isEmpty()) {
            $this->error('No models selected.');

            return;
        }

        if ($this->option('types-only')) {
            $this->generateTypeDefinitions();
        } else {
            $this->generateTypeDefinitions();
            $this->generateModelClasses();
        }

        $this->info('Generation completed successfully.');
    }

    protected function validateEnvironment(): bool
    {
        if (config('database.default') !== 'pgsql') {
            $this->error('Only PostgreSQL is supported at the moment.');

            return false;
        }

        $this->info('Using PostgreSQL database driver.');

        if (! $this->option('types-only')) {
            if (! is_dir($this->outputDir)) {
                mkdir($this->outputDir, 0755, true);
            }
        }

        $typesDir = dirname($this->typesOutputPath);
        if (! is_dir($typesDir)) {
            mkdir($typesDir, 0755, true);
        }

        return true;
    }

    protected function setupTenantConnection(): void
    {
        $this->driver = config('database.default');
        DB::setDefaultConnection($this->connection);
        config([
            "database.connections.{$this->connection}.database" => $this->database,
        ]);

        app('db')->extend($this->connection, function ($config, $name) {
            $config['database'] = $this->database;

            return app('db.factory')->make($config, $name);
        });

        DB::purge($this->connection);

        $this->info("Connected to tenant database: {$this->database}");
    }

    protected function shouldUseAllModels(): bool
    {
        $configModels = config('typegen.models', ['include' => []]);

        return isset($configModels['include'][0]) && $configModels['include'][0] === '*';
    }

    protected function loadModels(): void
    {
        if ($this->shouldUseAllModels()) {
            $this->info('Including all models...');
            $viewModels = collect(app('files')->files(app_path('Models/Views')))
                ->map(
                    fn ($file) => app()->getNamespace().'Models\\Views\\'.
                        Str::replaceLast('.php', '', str_replace('/', '\\', $file->getRelativePathname()))
                )
                ->filter(function ($modelClass) {
                    if (! class_exists($modelClass)) {
                        return false;
                    }
                    $reflection = new \ReflectionClass($modelClass);

                    return ! $reflection->isAbstract();
                });
            $this->models = collect(app('files')->files(app_path('Models')))
                ->map(
                    fn ($file) => app()->getNamespace().'Models\\'.
                        Str::replaceLast('.php', '', str_replace('/', '\\', $file->getRelativePathname()))
                )
                ->filter(function ($modelClass) {
                    if (! class_exists($modelClass)) {
                        return false;
                    }
                    $reflection = new \ReflectionClass($modelClass);

                    return ! $reflection->isAbstract();
                })
                ->merge($viewModels);
        } else {
            $this->info('Including specified models...');
            $configModels = config('typegen.models', ['include' => []]);
            $this->models = collect($configModels['include']);
        }
    }

    protected function getSelectedModels(): Collection
    {
        $modelNames = $this->models->map(fn ($model) => class_basename($model))->toArray();
        $selectedNames = multiselect('Select the models to generate:', $modelNames);

        $this->line('Selected models:');

        return $this->models->filter(function ($model) use ($selectedNames) {
            $className = class_basename($model);
            $selected = in_array($className, $selectedNames);
            if ($selected) {
                $this->line("    $className");
            }

            return $selected;
        });
    }

    protected function getTableColumns(string $tableName): Collection
    {
        return collect(DB::select(
            "
            SELECT column_name, data_type, is_nullable
            FROM information_schema.columns 
            WHERE table_schema = 'public' AND table_name = :tableName",
            ['tableName' => $tableName]
        ));
    }

    protected function generateTypeDefinitions(): void
    {
        $this->line('Generating TypeScript interfaces...');
        $modelNames = [];

        $interfaces = $this->selectedModels->map(function ($modelClass) use (&$modelNames) {
            $model = app()->make($modelClass);
            $className = class_basename($model);
            $this->info("Generating interface for $className...");

            $modelNames[] = $className;
            $columns = $this->getTableColumns($model->getTable());
            $properties = $this->generateInterfaceProperties($columns, $model);

            return "export interface $className {\n$properties\n}";
        })->implode("\n\n");

        // Add union type for dynamic indexing
        $unionTypes = implode(' | ', $modelNames);
        $interfaces .= "\n\nexport type ModelTypes = $unionTypes;";

        app('files')->put($this->typesOutputPath, $interfaces);
    }

    protected function generateInterfaceProperties(Collection $columns, $model): string
    {
        $properties = $columns->map(function ($column) {
            $db_type = $column->data_type;
            $type = config("typegen.mapping.$db_type", 'any');
            $attributeString = $column->is_nullable === 'YES' ? "$column->column_name?" : "$column->column_name";
            $typeEntry = "    $attributeString: $type;";

            if (Str::endsWith($column->column_name, '_id')) {
                $relationship = Str::beforeLast($column->column_name, '_id');
                $relatedModel = $this->findRelatedModel($relationship);
                if ($relatedModel) {
                    $relatedType = class_basename($relatedModel);
                    $this->line("    Found belongsTo relationship: $relatedType");
                    $typeEntry .= "\n    $relationship?: $relatedType;";
                }
            }

            $this->line("    Inserting type: $typeEntry from $db_type");

            return $typeEntry;
        })->implode("\n");

        // Add hasMany and other relationship methods
        $relationshipProperties = $this->getRelationshipProperties($model);
        if ($relationshipProperties) {
            $properties .= "\n".$relationshipProperties;
        }

        return $properties;
    }

    protected function getRelationshipProperties($model): string
    {
        $reflection = new \ReflectionClass($model);
        $relationships = [];

        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            // Check for ExportRelationship attribute
            $attributes = $method->getAttributes(\App\Attributes\ExportRelationship::class);

            if (! empty($attributes)) {
                $attribute = $attributes[0]->newInstance();
                $relationName = $method->name;
                $relatedType = class_basename($attribute->relatedModel);

                if ($attribute->isCollection()) {
                    $relationships[] = "    {$relationName}?: {$relatedType}[];";
                    $this->line("    Found #[ExportRelationship] collection: {$relationName} -> {$relatedType}[]");
                } else {
                    $relationships[] = "    {$relationName}?: {$relatedType};";
                    $this->line("    Found #[ExportRelationship] single: {$relationName} -> {$relatedType}");
                }
            }
        }

        return implode("\n", $relationships);
    }

    protected function generateModelClasses(): void
    {
        $this->line('Generating JavaScript model classes...');

        $this->selectedModels->each(function ($modelClass) {
            $model = app()->make($modelClass);
            $className = class_basename($model);
            $this->info("Generating class for {$className}Base...");

            $columns = $this->getTableColumns($model->getTable());
            $imports = $this->generateImports($columns, $className);
            $properties = $this->generateClassProperties($columns);
            $constructor = $this->generateConstructor($columns, $className);

            $this->writeModelFile($className, $imports, $properties, $constructor);
        });
    }

    protected function generateImports(Collection $columns, string $className): string
    {
        $imports = 'import { ';
        $columns->each(function ($column) use (&$imports) {
            if (Str::endsWith($column->column_name, '_id')) {
                $relationship = Str::beforeLast($column->column_name, '_id');
                $relatedModel = $this->findRelatedModel($relationship);
                if ($relatedModel) {
                    $relatedType = class_basename($relatedModel);
                    $this->line("    Found relationship: $relatedType");
                    $imports .= "$relatedType, ";
                }
            }
        });
        $imports .= "$className } from '\$models';";

        return $imports;
    }

    protected function generateClassProperties(Collection $columns): string
    {
        return $columns->map(function ($column) {
            $type = config("typegen.mapping.{$column->data_type}", 'any');
            $attributeName = $column->is_nullable === 'YES' ? "{$column->column_name}?" : $column->column_name;
            $typeEntry = "    $attributeName: $type;";

            if (Str::endsWith($column->column_name, '_id')) {
                $relationship = Str::beforeLast($column->column_name, '_id');
                $relatedModel = $this->findRelatedModel($relationship);
                if ($relatedModel) {
                    $relatedType = class_basename($relatedModel);
                    $typeEntry .= "\n    $relationship?: $relatedType;";
                }
            }

            return $typeEntry;
        })->implode("\n");
    }

    protected function generateConstructor(Collection $columns, string $className): string
    {
        $constructorBody = $columns->map(function ($column) {
            return "        this.{$column->column_name} = data.{$column->column_name};";
        })->implode("\n");

        return "\n    constructor(data: $className) {\n$constructorBody\n    }";
    }

    protected function findRelatedModel(string $relationship): ?string
    {
        return $this->models->first(function ($model) use ($relationship) {
            return class_basename($model) === Str::studly($relationship);
        });
    }

    protected function writeModelFile(string $className, string $imports, string $properties, string $constructor): void
    {
        $contents = <<<TS
$imports

export class {$className}Base implements $className {
$properties

$constructor
}
TS;

        file_put_contents("{$this->outputDir}{$className}.ts", $contents);
    }
}
