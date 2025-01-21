<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import Input from '$components/DataInput/Input.svelte';
  import Select from '$components/DataInput/Select.svelte';
  import { toaster } from '$components/Feedback/Toast/ToastHandler.svelte';
  import FieldsetWrapper from '$components/UI/FieldsetWrapper.svelte';
  import Header from '$components/UI/Header.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import { superUseForm } from '$lib/inertia';
  import type { Organization } from '$models';
  import type { SelectOption } from '$types/index';

  interface Props {
    organizationTypeOptions: SelectOption[];
  }

  let { organizationTypeOptions }: Props = $props();

  let form = superUseForm<Organization>({
    name: '',
    description: '',
    type: '',
  });

  function handleSumit(event: Event) {
    event.preventDefault();
    $form.post(route('organization.store'),{
      onError: (errors: Record<string,string>) => {
        console.log(errors);
        toaster.error("Failed to create organization");
      },
    });
  }
</script>

<AuthenticatedLayout>
  <Header>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Create Organization
    </h2>
  </Header>

  <form onsubmit={handleSumit}>
    <FieldsetWrapper>
      <Input label="Name" bind:value={$form.name} name="name" />
      <Input
        label="Description"
        bind:value={$form.description}
        name="description"
      />
      <Select
        label="Type"
        bind:value={$form.type}
        name="type"
        options={organizationTypeOptions}
      />
      <Button class="my-2">
        Save
      </Button>
    </FieldsetWrapper>
  </form>
</AuthenticatedLayout>
