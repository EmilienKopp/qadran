<script lang="ts">
  import {
    AllCommunityModule,
    type ICellRendererParams,
    ModuleRegistry,
    createGrid,
  } from 'ag-grid-community';
  import { onMount } from 'svelte';
  import { smartDuration } from '$lib/utils/formatting';
  ModuleRegistry.registerModules([AllCommunityModule]);

  let container: HTMLElement | null = null;
  let gridAPI = $state<any>(null);

  interface Props {
    rows: any[];
    columns: any[];
    lastFlex?: boolean;
    class?: string;
    editable?: boolean;
    singleClickEdit?: boolean;
    stopEditingWhenCellsLoseFocus?: boolean;
    
    // Parent has to declare as FUNCTION, not arrow function since "this" context should not be lost
    onCellValueChanged?: (event: any) => void;
  }

  let {
    rows = $bindable([]),
    columns = [],
    lastFlex = true,
    class: className = '',
    editable = false,
    singleClickEdit = false,
    stopEditingWhenCellsLoseFocus = true,
    onCellValueChanged = undefined,
  }: Props = $props();

  columns.forEach((col, index) => {
    // remove that pesky border when using custom elements
    if(col.cellRenderer) {
      col.cellClass = 'focus-within:border-0!';
    }

    col.cellRendererSelector = (params: ICellRendererParams) => {
      return {
        component: col.cellRenderer,
        params: {
          ...params,
          variant: col.variant,
        },
      };
    };

    col.cellRendererParams = {
      ...col.cellRendererParams,
      variant: col.variant,
    };
    if (lastFlex && index === columns.length - 1 && col.flex === undefined) {
      col.flex = 1;
    }
  });

  const defaultColDef = {
    editable: editable,
  };

  onMount(() => {
    if (container) {
      gridAPI = createGrid(container, {
        rowData: rows,
        columnDefs: columns,
        defaultColDef,
        onCellValueChanged,
        singleClickEdit,
        stopEditingWhenCellsLoseFocus,
      });
    }
  });

  $effect(() => {
    if (gridAPI) {
      gridAPI.setGridOption('rowData', rows);
    }
  });
  
  export function refresh() {
    if (gridAPI) {
      gridAPI.setGridOption('rowData', rows);
    }
  }

  export function getApi() {
    return gridAPI;
  }

</script>

<div
  id="grid-container-{crypto.randomUUID()}"
  bind:this={container}
  class={className}
></div>
