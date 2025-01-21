<script lang="ts">
  import Select from '$components/DataInput/Select.svelte';
  import { DataTable } from '$components/Display/DataTable';
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

  let role = $state(getUserRoleName());
  let roles = $state(getAllUserRoles());
  let roleOptions = $derived(asSelectOptions(roles));
  let context = $derived(new OrganizationTableContext(role));
  let headers = $derived(context.strategy.getHeaders());
  let actions = $derived(context.strategy.getActions());

</script>

<AuthenticatedLayout>
  <Header>
    <h2 class="flex items-center justify-between w-full font-semibold text-xl text-gray-800 leading-tight">
      Organizations
      <Select name="role" bind:value={role} options={roleOptions} />
    </h2>
  </Header>
  <DataTable data={organizations} {headers} {actions} />
</AuthenticatedLayout>
