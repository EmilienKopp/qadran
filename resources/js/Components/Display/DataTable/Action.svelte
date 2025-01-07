<script lang="ts">
  import type { TableAction } from '$types/components/Table';
  import { twMerge } from 'tailwind-merge';

  export let actions: TableAction<any>[];
  export let row: any;
</script>

<td class="text-center flex gap-2">
  {#each actions as action}
    {#if !action.hidden?.(row)}
      <button
        on:click={() => action.callback(row)}
        class={twMerge(
          'mx-1 px-1 hover:underline flex items-center gap-1s',
          action.css?.(row)
        )}
      >
        {#if action.icon}
          <svelte:component this={action.icon(row)} class="w-4 h-4" />
        {/if}
        {action.label}
      </button>
    {/if}
  {/each}
</td>
