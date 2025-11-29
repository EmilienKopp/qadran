<script lang="ts">
  import { GridButton } from '$components/AgGridCustom/GridButton.js';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import MasterGrid from '$components/MasterGrid/index.svelte';
  import {
    Table as TableIcon,
    TextCursorInput as FormIcon,
  } from 'lucide-svelte';
  import type {
    Activity,
    ActivityLog,
    ActivityType,
    DailyLog,
    Task,
    TaskCategory,
  } from '$models';
  import { router } from '@inertiajs/svelte';
  import dayjs from 'dayjs';
  import DailyLogInputForm from './DailyLogInputForm.svelte';
  import {
    AllCommunityModule,
    type ICellRendererParams,
    ModuleRegistry,
    createGrid,
  } from 'ag-grid-community';
  import { onMount } from 'svelte';
  import { smartDuration } from '$lib/utils/formatting';
  import Dialog from '$components/Modals/Dialog.svelte';
  import DailyLogGridForm from './DailyLogGridForm.svelte';
  import SmartDatePicker from '$components/SmartDatePicker.svelte';

  ModuleRegistry.registerModules([AllCommunityModule]);

  const MODE_STORAGE_KEY = '_qadran_daily_log_mode';

  interface Props {
    dailyLogs?: DailyLog[];
    taskCategories?: TaskCategory[];
    activityTypes?: ActivityType[];
    tasks?: Task[];
    date?: string | Date;
    activities?: Activity[];
    daysWithEntries?: string[];
  }

  let {
    dailyLogs = [],
    taskCategories = [],
    activityTypes = [],
    tasks = [],
    date = new Date(),
    activities = [],
    daysWithEntries = [],
  }: Props = $props();

  let dialog: Dialog;
  let selectedLog = $state<DailyLog | null>(null);
  let mode: 'form' | 'grid' = $state('grid');

  onMount(() => {
    const cleaners: (() => void)[] = [];

    const myGridElement = document.getElementById('myGrid');
    const savedMode = localStorage.getItem(MODE_STORAGE_KEY);
    console.log('Saved mode:', savedMode);
    if (savedMode === 'form' || savedMode === 'grid') {
      mode = savedMode;
    }
    if (myGridElement) {
      const api = createGrid(myGridElement, gridOptions);
      cleaners.push(() => api.destroy());
    }

    return () => {
      cleaners.forEach((callback) => callback());
    };
  });

  const logs = $derived(
    dailyLogs.map((log) => {
      const activity = log.activities?.map(
        (activity: any) =>
          `${activity.activity_type.name} (${smartDuration(activity.duration_seconds)})`
      );
      // Limit to longest 2 then show "+n more" if applicable
      if (activity && activity.length > 2) {
        const sortedActivities =
          log.activities?.sort(
            (a: any, b: any) => b.duration_seconds - a.duration_seconds
          ) ?? [];
        const topActivities = sortedActivities
          .slice(0, 2)
          .map(
            (activity: any) =>
              `${activity.activity_type.name} (${smartDuration(activity.duration_seconds)})`
          );
        const remainingCount = sortedActivities.length - 2;
        activity.length = 0; // Clear original array
        activity.push(...topActivities);
        activity.push(`+${remainingCount} more`);
      }
      return {
        ...log,
        activity: activity?.join(', '),
      };
    })
  );

  const gridOptions = $derived<any>({
    //TODO: `any` not great but ag-Grid types are complex and I want to have a simple string (non-existing key in object) as field
    rowData: logs,
    columnDefs: [
      { field: 'project_name', headerName: 'Project' },
      { field: 'duration' },
      {
        field: 'activity',
        flex: 1,
        onCellClicked: actionClickHandler,
        cellClass: 'focus-within:border-0! cursor-pointer',
        valueFormatter: (params: any) =>
          params.value || 'No activities recorded',
      },
      {
        headerName: 'Actions',
        variant: 'danger',
        flex: 0,
        cellRenderer: GridButton,
        cellRendererParams: { label: 'Delete', onClick: deleteHandler },
      },
    ],
  });

  function actionClickHandler(params: ICellRendererParams) {
    selectedLog = params.data;
    dialog.open();
  }

  function deleteHandler(data: ActivityLog) {
    if (confirm('Are you sure you want to delete this log?')) {
      const { clock_entry_id } = data;
      router.delete(
        route('activities.delete-entry', { clockEntry: clock_entry_id })
      );
    }
  }

  function onFinish() {
    dialog?.close();
  }

  function toggleMode() {
    mode = mode === 'form' ? 'grid' : 'form';
    localStorage.setItem(MODE_STORAGE_KEY, mode);
  }

  function handleDateChange(selectedDate: string) {
    router.get(
      route('activities.show', selectedDate),
      {},
      { preserveState: true, preserveScroll: true }
    );
  }
</script>

<AuthenticatedLayout>
  <header class="flex gap-10 items-center mb-4">
    <h1 class="text-2xl font-semibold">Daily Logs</h1>
    <SmartDatePicker
      value={date}
      {daysWithEntries}
      onchange={handleDateChange}
    />
  </header>
  <MasterGrid
    rows={logs}
    columns={gridOptions.columnDefs}
    class="h-96 w-full"
  />
</AuthenticatedLayout>

<Dialog title="Edit daily log" bind:this={dialog} class="w-2/3!">
  <div>
    <label class="swap swap-rotate mb-2">
      <input type="checkbox" onchange={toggleMode} />
      <div class="swap-on" title="Grid View">
        <TableIcon class="inline size-6 mr-2" />
      </div>
      <div class="swap-off" title="Form View">
        <FormIcon class="inline size-6 mr-2" />
      </div>
    </label>
  </div>
  {#if selectedLog !== null}
    {#if mode === 'form'}
      <DailyLogInputForm
        bind:log={selectedLog}
        {taskCategories}
        {activityTypes}
        {tasks}
      />
    {:else if mode === 'grid'}
      <DailyLogGridForm
        bind:log={selectedLog}
        {taskCategories}
        {activityTypes}
        {tasks}
        {onFinish}
        onClose={() => dialog.close()}
      />
    {/if}
  {/if}
</Dialog>
