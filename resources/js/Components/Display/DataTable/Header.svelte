<script lang="ts">
  import type { TableHeader } from '$types/components/Table';
  
  export let headers: TableHeader<any>[];
  export let hasActions = false;
</script>

<tr>
  {#each headers as header}
    <th class="bg-primary text-primary-content uppercase font-bold">
      {header.label}
    </th>
  {/each}
  {#if hasActions}
    <th class="bg-primary text-primary-content uppercase font-bold text-center">
      Actions
    </th>
  {/if}
</tr>