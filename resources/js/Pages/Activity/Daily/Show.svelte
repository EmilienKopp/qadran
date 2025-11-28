<script lang="ts">
  import MiniButton from '$components/Buttons/MiniButton.svelte';
  import OutlineButton from '$components/Buttons/OutlineButton.svelte';
  import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
  import NewLogModal from '$components/Modals/NewLogModal.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import { toast } from '$lib/stores';
  import type { Activity, ActivityType, DailyLog, Task, TaskCategory } from '$models';
  import { router, useForm } from '@inertiajs/svelte';
  import dayjs from 'dayjs';
  import DailyLogInputForm from './DailyLogInputForm.svelte';
  import {
    AllCommunityModule,
    ModuleRegistry,
    createGrid,
  } from 'ag-grid-community';
  import { onMount } from 'svelte';
  ModuleRegistry.registerModules([AllCommunityModule]);

  const gridOptions = {
    // Row Data: The data to be displayed.
    rowData: [
      { make: 'Tesla', model: 'Model Y', price: 64950, electric: true },
      { make: 'Ford', model: 'F-Series', price: 33850, electric: false },
      { make: 'Toyota', model: 'Corolla', price: 29600, electric: false },
    ],
    // Column Definitions: Defines the columns to be displayed.
    columnDefs: [
      { field: 'make' },
      { field: 'model' },
      { field: 'price' },
      { field: 'electric' },
    ],
  };

  // let myGridElement: HTMLElement | null = null;
  onMount(() => {
    const myGridElement = document.getElementById('myGrid');
    if (myGridElement) {
      createGrid(myGridElement, gridOptions);
    }
  });

  // export let activities: Activity[];
  // export let dailyLogs: DailyLog[];
  // export let taskCategories: TaskCategory[];
  // export let date: string;
  interface Props {
    dailyLogs?: DailyLog[];
    taskCategories?: TaskCategory[];
    activityTypes?: ActivityType[];
    tasks?: Task[];
    date?: string | Date;
    activities?: Activity[];
  }

  let {
    dailyLogs = [],
    taskCategories = [],
    activityTypes = [],
    tasks = [],
    date = new Date(),
    activities = [],
  }: Props = $props();

  console.log(dailyLogs, taskCategories, date, activities);

  let selectedDate = $state(dayjs(date).format('YYYY-MM-DD'));
  let logModalOpen = $state(false);
  let log: DailyLog;

  let form = useForm({
    date: selectedDate,
    activities: activities,
  });

  function handleDateSelection() {
    console.log(selectedDate);
    if (!selectedDate) return;
    router.get(route('activities.show', { date: selectedDate }));
  }

  async function saveAll() {
    $form.post(route('activities.store'), {
      onSuccess: () => {
        toast.success('Activities saved successfully.');
      },
      onError: () => {
        toast.error('Error saving activities.');
        console.log($form);
      },
    });
  }

  async function saveAllAndReturn() {
    $form.post(route('activities.store'), {
      onSuccess: () => {
        toast.success('Activities saved successfully.');
        router.get(route('activities.index'));
      },
      onError: () => {
        toast.error('Error saving activities.');
      },
    });
  }

  function showLogModal() {
    logModalOpen = true;
  }

  $effect(() => {
    $form.date = selectedDate;
    $form.activities = dailyLogs.flatMap((log) => {
      return log.activities;
    });
  });
</script>

<AuthenticatedLayout>
  <header class="grid grid-cols-5 gap-4 items-center mb-4">
    <div>
      <h1 class="text-2xl font-semibold">Daily Logs</h1>
      <input
        type="date"
        class="rounded bg-transparent w-44 text-sm"
        onchange={handleDateSelection}
        bind:value={selectedDate}
      />
    </div>
    <PrimaryButton on:click={saveAll} loading={$form.processing}
      >Save All</PrimaryButton
    >
    <PrimaryButton on:click={saveAllAndReturn} loading={$form.processing}
      >Save All and Go Back</PrimaryButton
    >
    <OutlineButton href={route('activities.index')}>
      {#if $form.isDirty}
        Discard Changes
      {:else}
        Go Back
      {/if}
    </OutlineButton>
  </header>

  <div class="grid 2xl:grid-cols-2 grid-cols-1 gap-4 my-3">
    {#if dailyLogs.length == 0}
      <div class="col-span-2">
        <p>No logs found for this date.</p>
        <MiniButton type="button" color="info" onclick={showLogModal}>
          Create a new log
        </MiniButton>
      </div>
    {:else}
      {#each dailyLogs as log, i}
        {#if log.activities}
          <DailyLogInputForm
            bind:log={dailyLogs[i]}
            {taskCategories}
            {activityTypes}
            {tasks}
          />
        {/if}
      {/each}
    {/if}
  </div>
</AuthenticatedLayout>
<NewLogModal bind:open={logModalOpen} />
