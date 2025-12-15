<script lang="ts">
  import Select from '$components/DataInput/Select.svelte';
  import { asSelectOptions } from '$lib/utils/formatting';
  import {
    getAllUserRoles,
    getSharedContext,
    getUserRoleName,
    shared,
  } from '$lib/inertia';
  import { RoleContext } from '$lib/stores/global/roleContext.svelte';
  import { TenantContext, useTenantContext } from '$lib/stores/global/tenantContext.svelte';

  RoleContext.selected = getUserRoleName();
  RoleContext.available = getAllUserRoles();

  const severalRolesAvailable =
    RoleContext.available.filter((r) => r !== 'user').length > 0;

  const memberships = shared('auth.memberships') || [];
  $inspect(memberships);
</script>

<div
  class="flex items-center justify-center gap-1 w-full px-8 mx-auto"
  class:hidden={!severalRolesAvailable}
>

  <Select
    name="role"
    bind:value={RoleContext.selected}
    options={asSelectOptions(RoleContext.available)}
  />

  {#if memberships.length}
    @
    <Select
      name="tenant"
      bind:value={TenantContext.current.tenant}
      options={asSelectOptions(memberships, 'organizationId', 'organizationName')}
    />
  {/if}
</div>
