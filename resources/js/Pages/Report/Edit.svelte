<script lang="ts">
  import Input from '$components/DataInput/Input.svelte';
  import Textarea from '$components/DataInput/Textarea.svelte';
  import Header from '$components/UI/Header.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import { superUseForm } from '$lib/inertia';
  import type { Report } from '$models';
  import Button from '$components/Actions/Button.svelte';
  import { toaster } from '$components/Feedback/Toast/ToastHandler.svelte';

  interface Props {
    report: Report;
  }

  let { report }: Props = $props();

  const form = superUseForm<Report>({
    name: report.name,
    description: report.description,
  });

  function handleSubmit(e: Event) {
    e.preventDefault();
    $form.patch(route('report.update', report.id), {
      onSuccess: () => {
        toaster.success('Report updated successfully');
      },
      onError: (errors: Record<string,string>) => {
        toaster.error('Failed to update report');
        console.log(errors);
      },
    });
  }
</script>

<AuthenticatedLayout>
  <Header>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Edit {report.name}
    </h2>
  </Header>

  <div class="p-8">
    <form onsubmit={handleSubmit} class="grid grid-cols-1 gap-4">
      <Input label="Name" name="name" bind:value={$form.name} />
      <Textarea
        label="Description"
        name="description"
        bind:value={$form.description}
      />
      <Button>Update Report</Button>
    </form>
  </div>
</AuthenticatedLayout>