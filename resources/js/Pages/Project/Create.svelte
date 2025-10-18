<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import { DataTable } from '$components/Display/DataTable';
  import Header from '$components/UI/Header.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import type { Project } from '$models';
  import { superUseForm } from '$lib/inertia';
  import { Link, page, useForm } from '@inertiajs/svelte';
  import Input from '$components/DataInput/Input.svelte';
  import Textarea from '$components/DataInput/Textarea.svelte';
  import { toaster } from '$components/Feedback/Toast/ToastHandler.svelte';
  import Select from '$components/DataInput/Select.svelte';
  import { asSelectOptions } from '$lib/utils/formatting';

  interface Props {
    projects: any[];
    children?: import('svelte').Snippet;
  }

  const user = $page.props.auth.user;

  const form = superUseForm<Project>({
    name: '',
    description: '',
    organization_id: undefined,
  });

  function handleSubmit(e: Event) {
    e.preventDefault();
    $form.post(route('project.store'), {
      onSuccess: () => {
        toaster.success('Project created successfully');
      },
      onError: (errors: Record<string,string>) => {
        const messages = Object.values(errors).join('\n');
        toaster.error(messages);
      },
    });
  }
</script>

<AuthenticatedLayout>
  <div class="p-8">
    <form onsubmit={handleSubmit}>
      <Header title="Projects">
        <Button>Save</Button>
      </Header>
      <Input label="Name" name="name" bind:value={$form.name} />
      <Textarea
        label="Description"
        name="description"
        bind:value={$form.description}
      />
      <Select
        label="Organization"
        name="organization_id"
        options={asSelectOptions(user.organizations, 'id', 'name')}
        bind:value={$form.organization_id}
      ></Select>
    </form>
  </div>
</AuthenticatedLayout>
