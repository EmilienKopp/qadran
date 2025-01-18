<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import Select from '$components/DataInput/Select.svelte';
  import { toaster } from '$components/Feedback/Toast/ToastHandler.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import { ClockEntry } from '$lib/domain/ClockEntry';
  import { superUseForm } from '$lib/inertia';
  import { asSelectOptions } from '$lib/utils/formatting';
  import type { User, Project } from '$models';

  interface Props {
    user: User;
  }

  let { user }: Props = $props();
  let projectOptions = asSelectOptions<Project>(user.projects, 'id', 'name');

  let form = superUseForm({
    project_id: undefined,
  });

  
  
</script>

<svelte:head>
  <title>Dashboard</title>
</svelte:head>

<AuthenticatedLayout>
  {#snippet header()}
    <h2  class="text-xl font-semibold leading-tight text-gray-800">
      Dashboard
    </h2>
  {/snippet}

  <div class="py-12 w-full">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 w-full">
      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg w-full">
        <div class="p-6 text-gray-900 flex flex-col items-center justify-between w-full">
          <Select label="Select a project" bind:value={$form.project_id} options={projectOptions} />
          <Button onclick={() => ClockEntry.push($form)} class="mt-4">
            Submit
          </Button>
        </div>
      </div>
    </div>
  </div>
</AuthenticatedLayout>
