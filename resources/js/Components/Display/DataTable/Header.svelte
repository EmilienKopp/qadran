<script lang="ts">

  import type { TableHeader } from "$lib/types/components/Table";
  
  interface Props {
    headers: TableHeader<any>[];
    hasActions?: boolean;
  }

  let { headers, hasActions = false }: Props = $props();
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