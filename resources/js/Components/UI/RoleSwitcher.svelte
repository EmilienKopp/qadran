<script lang="ts">
  import Select from '$components/DataInput/Select.svelte';
  import { asSelectOptions } from '$lib/utils/formatting';
  import { getAllUserRoles, getUserRoleName } from '$lib/inertia';
  import { RoleContext } from '$lib/stores/global/roleContext.svelte';

  RoleContext.selected = getUserRoleName();
  RoleContext.available = getAllUserRoles();
</script>

<div
  class="form-control w-full max-w-xs"
  class:hidden={RoleContext.available.filter((r) => r !== 'user').length === 0 }
>
  <Select
    name="role"
    bind:value={RoleContext.selected}
    options={asSelectOptions(RoleContext.available)}
    class="select-primary select w-full shadow-xs transition-all hover:shadow-md"
  />
</div>
