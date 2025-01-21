<script lang="ts">
  import Input from '$components/DataInput/Input.svelte';
  import Select from '$components/DataInput/Select.svelte';
  import Textarea from '$components/DataInput/Textarea.svelte';
  import Header from '$components/UI/Header.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import { superUseForm } from '$lib/inertia';
  import type { Project } from '$models';
  import type { SelectOption } from '$types/index';

  interface Props {
    project: Project;
    statusOptions: SelectOption[];
  }

  let { project, statusOptions }: Props = $props();

  const form = superUseForm<Project>({
    name: project.name,
    description: project.description,
    start_date: project.start_date,
    end_date: project.end_date,
    status: project.status,
  });

  function handleSubmit(e: Event) {
    e.preventDefault();
    $form.patch(route('projects.update', project.id));
  }
</script>

<AuthenticatedLayout>
  <Header>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {project.name}
    </h2>
  </Header>

  <form onsubmit={handleSubmit} class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <Input label="Name" name="name" bind:value={$form.name} />
    <Select
      label="Status"
      name="status"
      bind:value={$form.status}
      options={statusOptions}
    />

    <Input
      type="date"
      label="Start Date"
      name="start_date"
      bind:value={$form.start_date}
    />
    <Input
      type="date"
      label="End Date"
      name="end_date"
      bind:value={$form.end_date}
    />
    <Textarea
      label="Description"
      name="description"
      bind:value={$form.description}
      class="col-span-2"
    />
  </form>
</AuthenticatedLayout>
