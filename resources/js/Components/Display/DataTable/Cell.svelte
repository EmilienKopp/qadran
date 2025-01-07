<script lang="ts">
  import { FilterService } from '$lib/utils/highlight';
  import { resolveNestedValue } from '$lib/utils/objects';
  import type { TableHeader } from '$types/components/Table';

  export let row: any;
  export let header: TableHeader<any>;
  export let searchStrings: string[] = [];
  export let onRowClick: ((row: any) => void) | undefined = undefined;

  $: value = resolveNestedValue(row, header.key);

  function handleClick() {
    if (onRowClick) {
      onRowClick(row);
    }
  }
</script>

<td class="whitespace-nowrap max-w-72 truncate" on:click|self={handleClick}>
  <div class="flex gap-1 items-center">
    {#if header.icon}
      <span title={value} class={header.iconClass?.(row)}>
        <svelte:component this={header.icon(row)} class="w-5 h-5" />
      </span>
    {/if}
    {#if !header.iconOnly}
      {#if header.combined}
        {header.combined(row)}
      {:else if value === null || value === undefined}
        -
      {:else}
        {@const formatted = header.formatter ? header.formatter(value) : value}
        {#if searchStrings?.length && typeof formatted === 'string' && header.searchable}
          <p>
            {@html FilterService.highlightMany(
              formatted,
              searchStrings,
              ['bg-yellow-100', 'bg-blue-100'],
              'exact'
            )}
          </p>
        {:else}
          {formatted}
        {/if}
      {/if}
    {/if}
  </div>
</td>
