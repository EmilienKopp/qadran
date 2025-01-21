<script lang="ts">
  import type { TableAction } from '$types/components/Table';
  import { Link } from '@inertiajs/svelte';
  import { twMerge } from 'tailwind-merge';

  interface Props {
    actions: TableAction<any>[];
    row: any;
  }

  let { actions, row }: Props = $props();
</script>

<td class="text-center flex justify-center gap-2">
  {#each actions as action}
    {#if !action.hidden?.(row)}
      <Link
        onclick={() => action.callback?.(row)}
        class={twMerge(
          'mx-1 px-1 hover:underline flex items-center gap-1s',
          action.css?.(row)
        )}
        href={action.href?.(row) ?? "#"}
      >
        {#if action.icon}
          {@const SvelteComponent = action.icon(row)}
          <SvelteComponent class="w-4 h-4" />
        {/if}
        {action.label}
      </Link>
    {/if}
  {/each}
</td>
