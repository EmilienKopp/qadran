<script lang="ts">
  import Modal from '$components/Actions/Modal.svelte';
  import Select from '$components/DataInput/Select.svelte';
  import DataList from '$components/Display/DataList.svelte';
  import { DataTable } from '$components/Display/DataTable';
  import type { TableAction } from '$types/common/table';
  import Header from '$components/UI/Header.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import { OrganizationTableContext } from '$lib/domain/Organization/context';
  import { getAllUserRoles, getUserRoleName } from '$lib/inertia';
  import { asSelectOptions } from '$lib/utils/formatting';
  import type { Organization } from '$models';

  interface Props {
    organizations: Organization[];
  }

  let { organizations }: Props = $props();
  let modal: Modal | undefined = $state();
  let selectedOrganization: Organization | undefined = $state();

  const commonActions: TableAction<Organization>[] = [
    { label: 'View', callback: modalOpen, position: 1 },
  ];

  let role = $state(getUserRoleName());
  let roles = $state(getAllUserRoles());
  let roleOptions = $derived(asSelectOptions(roles));
  let context = $derived(new OrganizationTableContext(role));
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
      class="flex items-center justify-between w-full font-semibold text-xl text-gray-800 leading-tight"
    >
      Organizations
      <Select name="role" bind:value={role} options={roleOptions} />
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
