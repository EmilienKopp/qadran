<script lang="ts">
  import Select from '$components/DataInput/Select.svelte';
  import { asSelectOptions } from '$lib/utils/formatting';
  import {
    getAllUserRoles,
    getSharedContext,
    getUserRoleName,
  } from '$lib/inertia';
  import { RoleContext } from '$lib/stores/global/roleContext.svelte';
  import { TenantContext, useTenantContext } from '$lib/stores/global/tenantContext.svelte';

  RoleContext.selected = getUserRoleName();
  RoleContext.available = getAllUserRoles();

  useTenantContext();
  $inspect(TenantContext.current, TenantContext.available);
</script>

<div
  class="flex items-center justify-center gap-1 w-full px-8 mx-auto"
  class:hidden={RoleContext.available.filter((r) => r !== 'user').length === 0}
>
  <Select
    name="role"
    bind:value={RoleContext.selected}
    options={asSelectOptions(RoleContext.available)}
  />

  {#if TenantContext.current?.tenant && (TenantContext.available?.length ?? 0) > 1}
    @
    <Select
      name="tenant"
      bind:value={TenantContext.current.tenant}
      options={asSelectOptions(TenantContext.available, 'tenant_id', 'name')}
    />
  {/if}
</div>
