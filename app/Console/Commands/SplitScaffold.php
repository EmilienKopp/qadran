<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class SplitScaffold extends Command
{
    protected $signature = 'split:scaffold {model : The name of the model}';

    protected $description = 'Generate Svelte pages (Index, Create, Show, Edit) for a model';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $model = $this->argument('model');
        $pascalCaseModel = Str::studly($model);
        $lowerCaseModel = Str::lower($model);
        $pluralModel = Str::plural($lowerCaseModel);

        $pagesPath = resource_path("js/Pages/{$pascalCaseModel}");

        // Create pages directory
        if (! $this->files->isDirectory($pagesPath)) {
            $this->files->makeDirectory($pagesPath, 0755, true);
            $this->info("Created directory: {$pagesPath}");
        }

        // Generate Index.svelte
        $indexContent = $this->generateIndexPage($pascalCaseModel, $lowerCaseModel, $pluralModel);
        $this->files->put("{$pagesPath}/Index.svelte", $indexContent);
        $this->info("Created: {$pagesPath}/Index.svelte");

        // Generate Create.svelte
        $createContent = $this->generateCreatePage($pascalCaseModel, $lowerCaseModel);
        $this->files->put("{$pagesPath}/Create.svelte", $createContent);
        $this->info("Created: {$pagesPath}/Create.svelte");

        // Generate Show.svelte
        $showContent = $this->generateShowPage($pascalCaseModel, $lowerCaseModel);
        $this->files->put("{$pagesPath}/Show.svelte", $showContent);
        $this->info("Created: {$pagesPath}/Show.svelte");

        // Generate Edit.svelte
        $editContent = $this->generateEditPage($pascalCaseModel, $lowerCaseModel);
        $this->files->put("{$pagesPath}/Edit.svelte", $editContent);
        $this->info("Created: {$pagesPath}/Edit.svelte");

        $this->info("Svelte pages for '{$pascalCaseModel}' generated successfully!");
    }

    protected function generateIndexPage(string $pascalCaseModel, string $lowerCaseModel, string $pluralModel): string
    {
        return <<<SVELTE
<script lang="ts">
  import Button from '\$components/Actions/Button.svelte';
  import { DataTable } from '\$components/Display/DataTable';
  import Header from '\$components/UI/Header.svelte';
  import AuthenticatedLayout from '\$layouts/AuthenticatedLayout.svelte';
  import { {$pascalCaseModel}Context } from '\$lib/domain/{$pascalCaseModel}/context';
  import { getAllUserRoles, getUserRoleName } from '\$lib/inertia';

  interface Props {
    {$pluralModel}: any[];
    children?: import('svelte').Snippet;
  }
  let role = \$state(getUserRoleName());
  let roles = \$state(getAllUserRoles());
  let context = \$state(new {$pascalCaseModel}Context(role));
  let headers = \$state(context.strategy.headers());
  let actions = \$state(context.strategy.actions());

  let { {$pluralModel} }: Props = \$props();

</script>

<AuthenticatedLayout>
  <div class="p-8">
    <Header title="{$pascalCaseModel}s">
      <Button href={route('{$lowerCaseModel}.create')}>Create {$pascalCaseModel}</Button>
    </Header>

    <DataTable
      data={{$pluralModel}}
      {headers}
      {actions}
    />
  </div>
</AuthenticatedLayout>
SVELTE;
    }

    protected function generateCreatePage(string $pascalCaseModel, string $lowerCaseModel): string
    {
        return <<<SVELTE
<script lang="ts">
  import Button from '\$components/Actions/Button.svelte';
  import Header from '\$components/UI/Header.svelte';
  import AuthenticatedLayout from '\$layouts/AuthenticatedLayout.svelte';
  import type { {$pascalCaseModel} } from '\$models';
  import { superUseForm } from '\$lib/inertia';
  import Input from '\$components/DataInput/Input.svelte';
  import Textarea from '\$components/DataInput/Textarea.svelte';
  import { toaster } from '\$components/Feedback/Toast/ToastHandler.svelte';

  interface Props {
    children?: import('svelte').Snippet;
  }

  const form = superUseForm<{$pascalCaseModel}>({
    name: '',
    description: '',
  });

  function handleSubmit(e: Event) {
    e.preventDefault();
    \$form.post(route('{$lowerCaseModel}.store'), {
      onSuccess: () => {
        toaster.success('{$pascalCaseModel} created successfully');
      },
      onError: (errors: Record<string,string>) => {
        toaster.error('Failed to create {$lowerCaseModel}');
        console.log(errors);
      },
    });
  }
</script>

<AuthenticatedLayout>
  <div class="p-8">
    <form onsubmit={handleSubmit}>
      <Header title="Create {$pascalCaseModel}">
        <Button>Save</Button>
      </Header>
      <Input label="Name" name="name" bind:value={\$form.name} />
      <Textarea
        label="Description"
        name="description"
        bind:value={\$form.description}
      />
    </form>
  </div>
</AuthenticatedLayout>
SVELTE;
    }

    protected function generateShowPage(string $pascalCaseModel, string $lowerCaseModel): string
    {
        return <<<SVELTE
<script lang="ts">
  import Button from "\$components/Actions/Button.svelte";
  import DataList from "\$components/Display/DataList.svelte";
  import FieldsetWrapper from "\$components/UI/FieldsetWrapper.svelte";
  import Header from "\$components/UI/Header.svelte";
  import AuthenticatedLayout from "\$layouts/AuthenticatedLayout.svelte";
  import { date } from "\$lib/utils/formatting";
  import type { {$pascalCaseModel} } from "\$models";

  interface Props {
    {$lowerCaseModel}: {$pascalCaseModel};
  }

  let { {$lowerCaseModel} }: Props = \$props();

  const headers = [
    { key: "name", label: "Name" },
    { key: "description", label: "Description" },
    { key: "created_at", label: "Created At", formatter: date },
    { key: "updated_at", label: "Updated At", formatter: date },
  ];
</script>

<AuthenticatedLayout>
  <Header>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{$lowerCaseModel}.name}
      
      <Button type="button" href={route('{$lowerCaseModel}.edit', {$lowerCaseModel}.id)} class="ml-4">
        Edit
      </Button>
    </h2>
  </Header>
  
  <FieldsetWrapper>
    <DataList {headers} data={{$lowerCaseModel}} />
  </FieldsetWrapper>
</AuthenticatedLayout>
SVELTE;
    }

    protected function generateEditPage(string $pascalCaseModel, string $lowerCaseModel): string
    {
        return <<<SVELTE
<script lang="ts">
  import Input from '\$components/DataInput/Input.svelte';
  import Textarea from '\$components/DataInput/Textarea.svelte';
  import Header from '\$components/UI/Header.svelte';
  import AuthenticatedLayout from '\$layouts/AuthenticatedLayout.svelte';
  import { superUseForm } from '\$lib/inertia';
  import type { {$pascalCaseModel} } from '\$models';
  import Button from '\$components/Actions/Button.svelte';
  import { toaster } from '\$components/Feedback/Toast/ToastHandler.svelte';

  interface Props {
    {$lowerCaseModel}: {$pascalCaseModel};
  }

  let { {$lowerCaseModel} }: Props = \$props();

  const form = superUseForm<{$pascalCaseModel}>({
    name: {$lowerCaseModel}.name,
    description: {$lowerCaseModel}.description,
  });

  function handleSubmit(e: Event) {
    e.preventDefault();
    \$form.patch(route('{$lowerCaseModel}.update', {$lowerCaseModel}.id), {
      onSuccess: () => {
        toaster.success('{$pascalCaseModel} updated successfully');
      },
      onError: (errors: Record<string,string>) => {
        toaster.error('Failed to update {$lowerCaseModel}');
        console.log(errors);
      },
    });
  }
</script>

<AuthenticatedLayout>
  <Header>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Edit {{$lowerCaseModel}.name}
    </h2>
  </Header>

  <div class="p-8">
    <form onsubmit={handleSubmit} class="grid grid-cols-1 gap-4">
      <Input label="Name" name="name" bind:value={\$form.name} />
      <Textarea
        label="Description"
        name="description"
        bind:value={\$form.description}
      />
      <Button>Update {$pascalCaseModel}</Button>
    </form>
  </div>
</AuthenticatedLayout>
SVELTE;
    }
}
