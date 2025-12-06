<script context="module" lang="ts">
  export let openPopoverId: string;
</script>

<script lang="ts">
  import ActivityLogItem from '$components/Activity/ActivityLogItem.svelte';
  import MiniPie from '$components/Charts/MiniPie.svelte';
  import Select from '$components/DataInput/Select.svelte';
  import ActivityPieChart from '$components/Charts/ActivityPieChart.svelte';
  import Dialog from '$components/Modals/Dialog.svelte';
  import { toast, user } from '$lib/stores';
  import { Duration } from '$lib/utils/duration';
  import { page, router, useForm } from '@inertiajs/svelte';
  import { onDestroy } from 'svelte';
  import { slide } from 'svelte/transition';
  import dayjs from 'dayjs';

  interface Props {
    log: any;
    detailsOpen?: boolean;
    id: string;
  }

  let { log, detailsOpen = false, id }: Props = $props();

  let open: boolean = false;
  let dialog: Dialog;
  let projectName: string = log?.project_name;
  let elapsedSeconds: number = 0;
  const interval = setInterval(() => {
    elapsedSeconds++;
  }, 1000);

  const timeSinceLogStart = $derived(
    log.start_time ? dayjs().diff(dayjs(log.start_time), 'second') : 0
  );

  const form = useForm({
    user_id: $user.id,
    project_id: log.project_id,
    date: null,
    in_project_id: null,
    out_project_id: null,
  });

  async function clock(e: Event) {
    e.preventDefault();
    const logId = log.timeLogs.find((l: any) => l.is_running)?.id;
    const target = e.target as HTMLAnchorElement;

    await $form.put(route('timelog.update', { timelog: logId }), {
      onSuccess: () => {
        toast.success('Clocked out successfully');
        log.is_running = false;
        if (target.tagName == 'A' && target.href) {
          router.get(target.href);
        }
      },
    });
  }

  function handleClick() {
    dialog.open();
    openPopoverId = open ? id : '';
  }

  let selectedLog: any = null;
  let fillModalOpen: boolean = false;
  let selectedCategoryId: number = 0;

  function handleFill(log: any) {
    selectedLog = log;
  }

  onDestroy(() => {
    clearInterval(interval);
  });
</script>

<div class="mx-3 p-3 rounded bg-transparent">
  <h3 class="text-lg font-semibold flex items-center gap-2">
    {projectName}

    <form class="dropdown" onsubmit={clock}>
      <input type="hidden" name="project_id" value={$form.project_id} />
      <button
        type="button"
        class="badge md:text-md text-xs whitespace-nowrap flex items-center"
        class:text-red-500={log.is_running}
      >
        {#if log.is_running || log.total_seconds === null}
          <span class="loading loading-infinity loading-xs mr-2"></span>
          {Duration.toHrMinString(timeSinceLogStart + elapsedSeconds)}
        {:else}
          {Duration.toHrMinString(Number(log.total_seconds))}
        {/if}
      </button>

      <ul
        class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52"
      >
        {#if log.is_running}
          <li><button onclick={clock}>Clock Out</button></li>
          <li>
            <a
              onclick={clock}
              href={route('activities.show', { date: log.date })}
            >
              Clock Out and Edit
            </a>
          </li>
        {/if}
        <li>
          <button type="button" onclick={() => handleFill(log)}>Fill</button>
          {#if fillModalOpen}
            <Select
              name="activity"
              label="Task Category"
              bind:value={selectedCategoryId}
              items={$page.props.taskCategories}
              mapping={{ labelColumn: 'name', valueColumn: 'id' }}
            />
          {/if}
        </li>
        <li><a href={route('activities.show', { date: log.date })}>Edit</a></li>
      </ul>
    </form>
    {#if log.activities?.length}
      <button
        {id}
        class="ml-2 cursor-pointer"
        title="Click to enlarge"
        onclick={handleClick}
        onmouseenter={() => (openPopoverId = id)}
      >
        <MiniPie data={log.activities.map((a) => a.duration_seconds)} />
      </button>
    {:else if !log.is_running}
      <a href={route('activities.show', { date: log.date })}>
        <!-- PencilSquare -->
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="16"
          height="16"
          fill="currentColor"
          class="bi bi-pencil-square"
          viewBox="0 0 16 16"
        >
          <path
            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"
          />
          <path
            fill-rule="evenodd"
            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"
          />
        </svg>
      </a>
    {/if}
  </h3>
  {#if detailsOpen}
    <div class="flex flex-col gap-1" transition:slide>
      {#each log.activities as activity}
        <ActivityLogItem {activity} />
      {/each}
    </div>
  {/if}
</div>

<Dialog bind:this={dialog} title={projectName}>
  <ActivityPieChart data={log.activities} />
</Dialog>
