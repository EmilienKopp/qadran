<script lang="ts">
  import { run } from 'svelte/legacy';

  import { query } from '$lib/stores';
  import { exists } from '$lib/utils/assessing';
  import type { TableProps } from '$types/components/Table';
  import { twMerge } from 'tailwind-merge';
  import TableActions from './Action.svelte';
  import TableCell from './Cell.svelte';
  import TableHeader from './Header.svelte';
  import Pagination from './Pagination.svelte';

  interface Props {
    data?: TableProps<any>['data'] | TableProps<any>['paginatedData'];
    paginated?: TableProps<any>['paginated'];
    paginatedData?: TableProps<any>['paginatedData'];
    headers?: TableProps<any>['headers'];
    onRowClick?: TableProps<any>['onRowClick'];
    onDelete?: TableProps<any>['onDelete'];
    model?: TableProps<any>['model'];
    className?: string;
    actions?: TableProps<any>['actions'];
    searchStrings?: TableProps<any>['searchStrings'];
  }

  let {
    data = undefined,
    paginated = false,
    paginatedData = undefined,
    headers = [],
    onRowClick = undefined,
    onDelete = undefined,
    model = 'user',
    className = '',
    actions = undefined,
    searchStrings = $bindable([])
  }: Props = $props();

  if (!searchStrings?.length && $query.param('search')) {
    searchStrings = [$query.param('search')?.toString() || ''];
  }

  let pageIndex = $derived($query.param('page') || 1);

  let hasActions = $derived(Boolean(onDelete || actions?.length));

  $inspect(data);
</script>

{#if data?.length === 0}
  <div class="text-center text-gray-500 p-4">No data available</div>
{:else}
  <div class="overflow-x-auto shadow-md rounded-lg">
    <table class="table table-zebra table-sm w-full {className}">
      <thead>
        <TableHeader {headers} {hasActions} />
      </thead>

      <tbody>
        {#each data ?? [] as row (row.id)}
          <tr class={twMerge(
            'hover:bg-base-300 transition-colors duration-200',
            onRowClick && 'cursor-pointer'
          )}>
            {#each headers as header}
              <TableCell {row} {header} {searchStrings} {onRowClick} />
            {/each}
            
            {#if actions?.length}
              <TableActions {actions} {row} />
            {/if}
          </tr>
        {/each}
      </tbody>
    </table>
  </div>

  {#if paginated}
    <Pagination paginatedData={data} pageIndex={Number(pageIndex)} />
  {/if}
{/if}

<style>
  table {
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 0.75rem;
    overflow: hidden;
  }

  thead th {
    position: sticky;
    top: 0;
    z-index: 10;
  }
</style>