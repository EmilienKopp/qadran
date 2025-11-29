<script lang="ts">
  import { onMount } from 'svelte';
  import { smartDuration } from '$lib/utils/formatting';
  import MasterGrid from '$components/MasterGrid/index.svelte';
  import type { DailyLog, TaskCategory, ActivityType, Task } from '$models';
  import { Form, router } from '@inertiajs/svelte';
  import { duration } from 'dayjs';
  import { superUseForm } from '$lib/inertia';
  import { toaster } from '$components/Feedback/Toast/ToastHandler.svelte';
  import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
  import { GridButton } from '$components/AgGridCustom/GridButton';
  import { edit } from '../../../routes/clock-entry';
  import OutlineButton from '$components/Buttons/OutlineButton.svelte';
  import MiniButton from '$components/Buttons/MiniButton.svelte';

  interface Props {
    taskCategories?: TaskCategory[];
    activityTypes?: ActivityType[];
    tasks?: Task[];
    log: DailyLog | null;
    onFinish?: () => any;
    onSuccess?: (response: any) => void;
    onError?: (errors: any) => void;
  }

  let {
    activityTypes = [],
    log = $bindable(),
    tasks = [],
    onFinish,
    onSuccess,
    onError,
  }: Props = $props();

  let grid: MasterGrid;
  let deleted = $state<number[]>([]);

  let activities = $state(
    log?.activities
      ? log.activities.map((activity: any) => ({
          ...activity,
          hours: Math.floor((activity.duration_seconds ?? 0) / 3600),
          minutes: Math.floor(((activity.duration_seconds ?? 0) % 3600) / 60),
        }))
      : []
  );

  const activitiesTotal = $derived(
      activities.reduce((a: number, b: any) => a + b.duration_seconds, 0)
  );

  const columnDefs = [
    {
      field: 'activity_type.name',
      headerName: 'Activity Type',
      flex: 1,
      cellEditor: 'agSelectCellEditor',
      cellEditorParams: {
        values: activityTypes.map((type) => type.name),
      },
      valueSetter: (params: any) => {
        const selectedType = activityTypes.find(
          (type) => type.name === params.newValue
        );
        if (selectedType) {
          params.data.activity_type = selectedType;
          params.data.activity_type_id = selectedType.id;
          return true;
        }
        return false;
      },
    },
    {
      field: 'hours',
      headerName: 'Hours',
      width: 80,
      resizable: false,
      cellEditor: 'agNumberCellEditor',
      cellEditorParams: { min: 0, max: 23 },
    },
    {
      field: 'minutes',
      headerName: 'Minutes',
      width: 100,
      resizable: false,
      cellEditor: 'agNumberCellEditor',
      cellEditorParams: { min: 0, max: 59 },
    },
    {
      field: 'actions',
      cellRenderer: GridButton,
      resizable: false,
      editable: false,
      sortable: false,
      width: 100,
      tooltipField: 'Fill the remaining time',
      cellRendererParams: {
        onClick: fill,
        label: 'Autofill',
      },
    },
    {
      headerName: '',
      cellRenderer: GridButton,
      resizable: false,
      editable: false,
      sortable: false,
      width: 90,
      variant: 'outline',
      tooltipField: 'Reset the time to zero',
      cellRendererParams: {
        onClick: reset,
        label: 'Reset',
      }
    },
    {
      headerName: '',
      flex: 2,
      cellRenderer: GridButton,
      resizable: false,
      editable: false,
      sortable: false,
      variant: 'danger',
      tooltipField: 'Delete this activity',
      cellRendererParams:{
        onClick: deleteHandler,
        label: 'Delete',
      },
    },
  ];

  onMount(() => {
    // Focus first editable text or number cell on mount
    grid.getApi().forEachNode((node: any) => {
      if (node.rowIndex === 0) {
        grid.getApi().startEditingCell({
          rowIndex: 0,
          colKey: 'activity_type.name',
        });
      }
    });
  })

  function fill(data: any) {
    if(!log?.total_seconds) return;
    const totalAvailable = log.total_seconds;
    const toFill = totalAvailable - activitiesTotal;
    data.duration_seconds += toFill;
    data.hours = Math.floor(data.duration_seconds / 3600);
    data.minutes = Math.floor((data.duration_seconds % 3600) / 60);
  }

  function reset(data: any) {
    if(!log) return;
    data.duration_seconds = 0;
    data.hours = 0;
    data.minutes = 0;
  }

  function deleteHandler(data: any) {
    if(!log) return;
    activities = activities.filter((activity: any) => activity.id !== data.id);
    deleted.push(data.id);
  }

  function valueChangeHandler(event: any) {
    console.log('Cell value changed:', event);
    const activity = event.data;
    const hours = activity.hours ?? 0;
    const minutes = activity.minutes ?? 0;
    activity.duration_seconds = hours * 3600 + minutes * 60;
    if (!log?.activities) return;
    let index = log.activities.findIndex((a: any) => a.id === activity.id);
    if (index === -1) {
      return;
    }
    log.activities[index] = {
      ...activity,
    };
  }

  function submit(e: Event) {
    e.preventDefault();
    console.log('Submitting activities:', activities);
    router.post(
      route('activities.store'),
      { activities, deleted },
      {
        onError,
        onSuccess,
        onFinish,
      }
    );
  }

  function addActivity() {
    activities = [
      ...activities,
      {
        activity_type_id: activityTypes?.at(0)?.id || null,
        activity_type: activityTypes?.at(0) || null,
        clock_entry_id: log?.clock_entry_id || null,
        duration_seconds: 0,
        hours: 0,
        minutes: 0,
      },
    ];
    grid.refresh();
  }
</script>

<form onsubmit={submit}>
  <div id="daily-log-grid-form-container">
    <MasterGrid
      bind:rows={activities}
      bind:this={grid}
      columns={columnDefs}
      class="h-72 max-h-96 w-full"
      onCellValueChanged={valueChangeHandler}
      editable singleClickEdit
    />
  </div>
  <MiniButton type="button" class="mt-2" onclick={addActivity}>
    Add Activity
  </MiniButton>
  <div class="mt-4 font-semibold">
    Total Logged Time: {smartDuration(activitiesTotal)} 
    / {log ? smartDuration(log.total_seconds) : 'N/A'}
  </div>
  <div class="w-full grid grid-cols-2 gap-6 mt-6">
    <OutlineButton>
      Cancel
    </OutlineButton>
    <PrimaryButton type="submit">Save</PrimaryButton>
  </div>
</form>
