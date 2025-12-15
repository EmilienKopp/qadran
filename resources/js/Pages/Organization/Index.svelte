<script lang="ts">
  import Modal from '$components/Actions/Modal.svelte';
  import DataList from '$components/Display/DataList.svelte';
  import { DataTable } from '$components/Display/DataTable';
  import type { DataAction } from '$types/common/dataDisplay';
  import Header from '$components/UI/Header.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import { OrganizationContext } from '$lib/domain/Organization/context';
  import type { Organization } from '$models';
  import { RoleContext } from '$lib/stores/global/roleContext.svelte';
  import Button from '$components/Actions/Button.svelte';
  import organization from '../../routes/organization';

  interface Props {
    organizations: Organization[];
  }

  let { organizations }: Props = $props();
  let modal: Modal | undefined = $state();
  let selectedOrganization: Organization | undefined = $state();

  const commonActions: DataAction<Organization>[] = [
    { label: 'View', callback: modalOpen, position: 1 },
  ];

  let context = $derived(new OrganizationContext(RoleContext.selected));
  let headers = $derived(context.strategy.headers());
  let actions = $derived(context.strategy.actions(commonActions));

  function modalOpen(org: Organization) {
    selectedOrganization = org;
    modal?.showModal();
  }
</script>

<AuthenticatedLayout>
  <Header>
    <h2
      class="flex items-center justify-between w-full font-semibold text-xl  leading-tight"
    >
      Organizations (viewing as {RoleContext.selected})
      <Button href={route('organization.create')}>Create</Button>
    </h2>
  </Header>
  <DataTable data={organizations} {headers} {actions} />
</AuthenticatedLayout>

<Modal bind:this={modal}>
  {#snippet title()}
    Organization Details
  {/snippet}

  <DataList data={selectedOrganization} {headers} />

  <div class="flex justify-end">
    <button type="button" class="btn" onclick={modal.close}> Close </button>
  </div>
</Modal>
