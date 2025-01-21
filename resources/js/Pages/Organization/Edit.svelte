<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import Input from '$components/DataInput/Input.svelte';
  import Select from '$components/DataInput/Select.svelte';
  import FieldsetWrapper from '$components/UI/FieldsetWrapper.svelte';
  import Header from '$components/UI/Header.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import { superUseForm } from '$lib/inertia';
  import type { Organization } from '$models';
  import type { SelectOption } from '$types/index';

  interface Props {
    organization: Organization;
    organizationTypeOptions: SelectOption[];
  }

  let { organization, organizationTypeOptions }: Props = $props();

  let form = superUseForm<Organization>(organization);

  function handleSumit(event: Event) {
    event.preventDefault();
    $form.patch(route('organization.update', organization.id));
  }
</script>

<AuthenticatedLayout>
  <Header>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Edit Organization
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
      <Button>
        Save
      </Button>
    </FieldsetWrapper>
  </form>
</AuthenticatedLayout>
