<script lang="ts">
  
  import MiniButton from '$components/Buttons/MiniButton.svelte';
  import OutlineButton from '$components/Buttons/OutlineButton.svelte';
  import PrimaryButton from '$components/Buttons/PrimaryButton.svelte';
  import DurationInput from '$components/Inputs/DurationInput.svelte';
  import Select from '$components/Inputs/Select.svelte';
  import Dialog from '$components/Modals/Dialog.svelte';
  import TimeZoneInfo from '$components/Widgets/TimeZoneInfo.svelte';
  import { toast } from '$lib/stores';
  import { Duration } from '$lib/utils/duration';
  import type { TaskCategory } from '$models';
  import route from '$vendor/tightenco/ziggy';
  import { useForm } from '@inertiajs/svelte';
  import dayjs from 'dayjs';
  import timezone from 'dayjs/plugin/timezone';
  import utc from 'dayjs/plugin/utc';
  import { fade } from 'svelte/transition';

  dayjs.extend(timezone);
  dayjs.extend(utc);

  export let taskCategories: TaskCategory[];
  export let log: any;

  const logEntry = log.timeLogs[0];
  let aboveMax: boolean = true;
  let loading: boolean = false;
  let safetyOn: boolean = true;
  let clockEntryModalOpen = false;

  const clockEntriesForm = useForm({
    entries: log.timeLogs.map((entry: any) => {
      return {
        ...entry,
        in_time: dayjs(entry.in_time).format('YYYY-MM-DDTHH:mm'),
        out_time: entry.out_time
          ? dayjs(entry.out_time).format('YYYY-MM-DDTHH:mm')
          : '',
      };
    }),
  });

  const form = useForm({
    date: log.date,
    project_id: log.project_id,
    activities: log.activities.filter(
      (a: any) => a.project_id == log.project_id
    ),
  });

  $: activitiesTotal = $form.activities
    .filter((a: any) => a.project_id == log.project_id)
    .reduce((a: number, b: any) => a + b.duration, 0);

  $: if (
    safetyOn &&
    log?.total_seconds &&
    Duration.flooredToMinute(activitiesTotal) >
      Duration.flooredToMinute(log.total_seconds)
  ) {
    toast.show(
      'Total duration cannot be greater than ' +
        Duration.toHHMM(log.total_seconds),
      'error'
    );
    aboveMax = true;
  } else {
    aboveMax = false;
  }

  function opentTaskModal(activity: any, index: number) {
    console.log(activity, index);
  }

  function handleKeydown(e: CustomEvent, activity: any, index: number) {
    if (e.detail.key == 'Enter') {
      addRow(activity.project_id);
      if (document)
        document
          ?.querySelector(
            `input[name="activity_${activity.project_id}[${index + 1}]"]`
          )
          ?.focus();
    }
  }

  function addRow(projectId: number) {
    $form.activities = [
      ...$form.activities,
      {
        project_id: projectId,
        task_category_id: null,
        user_id: log.user_id,
        date: log.date,
        duration: 0,
      },
    ];
    log.activities = $form.activities;
    console.log(log.activities);
  }

  async function save() {
    loading = true;
    console.log($form);
    $form.post(route('activities.store'), {
      onSuccess: () => {
        toast.success('Activities saved successfully');
        $form.isDirty = false;
        loading = false;
      },
      onError: (error: any) => {
        toast.error('Error saving activities');
        console.log(error);
      },
    });
  }

  async function handleDelete() {
    console.log($form);
    if (confirm('Are you sure you want to delete this log?')) {
      $form.delete(route('timelog.destroy', { timelog: logEntry.id }), {
        onStart: () => {
          toast.info('Deleting log...');
        },
        onSuccess: () => {
          toast.success('Log deleted successfully');
        },
        onError: () => {
          toast.error('Error deleting log');
        },
      });
    }
  }

  function removeItem(index: number) {
    $form.activities = $form.activities.filter((a, i) => i != index);
  }

  function fill(index: any) {
    const remaining = log.total_seconds - activitiesTotal;
    $form.activities[index].duration = Math.round(
      $form.activities[index].duration + remaining
    );
  }

  function clear(index: any) {
    $form.activities[index].duration = 0;
  }

  async function updateClockEntries() {
    $clockEntriesForm.put(route('timelog.batch-update'), {
      onSuccess: () => {
        toast.success('Clock entries updated successfully');
        clockEntryModalOpen = false;
      },
      onError: () => {
        toast.error('Error updating clock entries');
        console.log($clockEntriesForm.errors);
      },
    });
  }

  $: log.activities = $form.activities;
</script>

<form
  class="rounded border p-5"
  on:submit|preventDefault={save}
  transition:fade
  class:border-green-600={!$form.isDirty}
  class:border-yellow-100={$form.isDirty && !aboveMax}
  class:border-red-600={aboveMax}
>
  {#if $form.processing}
    <div class="w-full h-full opacity-70 flex items-center justify-center">
      <span class="loading loading-dots loading-lg"></span>
    </div>
  {:else}
    <div class="flex justify-between text-lg">
      <h2>
        {log.date}・{log.project_name}・({Duration.toHrMinString(
          log.total_seconds
        )})
        <span
          class="tooltip"
          data-tip="Safety mode prevents you from saving activities that exceed the total duration of the log."
        >
          <button
            type="button"
            class="text-xs"
            class:text-red-500={!safetyOn}
            class:text-green-400={safetyOn}
            on:click={() => (safetyOn = !safetyOn)}
          >
            {#if safetyOn}
              <!-- ShieldFillCheck -->
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-shield-fill-check"
                viewBox="0 0 16 16"
              >
                <path
                  fill-rule="evenodd"
                  d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.8 11.8 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7 7 0 0 0 1.048-.625 11.8 11.8 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.54 1.54 0 0 0-1.044-1.263 63 63 0 0 0-2.887-.87C9.843.266 8.69 0 8 0m2.146 5.146a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793z"
                />
              </svg>
            {:else}
              <!-- ShieldSlash -->
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                fill="currentColor"
                class="bi bi-shield-slash"
                viewBox="0 0 16 16"
              >
                <path
                  fill-rule="evenodd"
                  d="M1.093 3.093c-.465 4.275.885 7.46 2.513 9.589a11.8 11.8 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7 7 0 0 0 1.048-.625 11.3 11.3 0 0 0 1.733-1.525l-.745-.745a10.3 10.3 0 0 1-1.578 1.392c-.346.244-.652.42-.893.533q-.18.085-.293.118a1 1 0 0 1-.101.025 1 1 0 0 1-.1-.025 2 2 0 0 1-.294-.118 6 6 0 0 1-.893-.533 10.7 10.7 0 0 1-2.287-2.233C3.053 10.228 1.879 7.594 2.06 4.06zM3.98 1.98l-.852-.852A59 59 0 0 1 5.072.559C6.157.266 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.483 3.626-.332 6.491-1.551 8.616l-.77-.77c1.042-1.915 1.72-4.469 1.29-7.702a.48.48 0 0 0-.33-.39c-.65-.213-1.75-.56-2.836-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524a50 50 0 0 0-1.357.39zm9.666 12.374-13-13 .708-.708 13 13z"
                />
              </svg>
            {/if}
          </button>
        </span>
      </h2>

      <div>
        <MiniButton
          class="text-xs mx-4"
          on:click={() => (clockEntryModalOpen = true)}
          >Edit clock entries</MiniButton
        >
        <MiniButton class="text-xs" on:click={handleDelete} color="warning"
          >Delete</MiniButton
        >
      </div>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th>Task Category</th>
          <th>Duration</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        {#each $form.activities as activity, index}
          <tr>
            <td>
              <Select
                name="activity_{activity.project_id}[{index}]"
                label="Task Category"
                bind:value={activity.task_category_id}
                items={taskCategories}
                mapping={{
                  labelColumn: 'name',
                  valueColumn: 'id',
                }}
              />
            </td>
            <td>
              <DurationInput
                bind:activity
                max={log.total_seconds}
                parentTotal={activitiesTotal}
                {safetyOn}
                on:minutekeydown={(e) => handleKeydown(e, activity, index)}
                on:hourkeydown={(e) => handleKeydown(e, activity, index)}
              />
            </td>
            <td>
              <div class="dropdown">
                <div
                  tabindex="0"
                  role="button"
                  class="btn m-1 btn-ghost btn-outline"
                >
                  <!-- ThreeDots -->
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="bi bi-three-dots"
                    viewBox="0 0 16 16"
                  >
                    <path
                      d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3"
                    />
                  </svg>
                  Options
                </div>
                <ul
                  class="p-2 shadow menu dropdown-content z-[1] bg-base-100 rounded-box w-52"
                >
                  {#if Duration.flooredToMinute(activitiesTotal) < Duration.flooredToMinute(log.total_seconds)}
                    <li>
                      <button type="button" on:click={() => fill(index)}
                        >Fill</button
                      >
                    </li>
                  {/if}
                  <li>
                    <button type="button" on:click={() => clear(index)}
                      >Clear</button
                    >
                  </li>
                  <li>
                    <button
                      type="button"
                      class="text-red-300"
                      on:click={() => removeItem(index)}>Remove</button
                    >
                  </li>
                  <li>
                    <button
                      type="button"
                      on:click={() => openTaskModal(activity, index)}>
                      Link Task
                    </button>
                  </li>
                  <li>
                    <button type="button" on:click={$form.reset()}>Reset</button
                    >
                  </li>
                </ul>
              </div>
            </td>
          </tr>
        {/each}
        <tr>
          <td colspan="3">
            <OutlineButton
              on:click={() => addRow(log.project_id)}
              disabled={$form.processing}
            >
              Add Activity
            </OutlineButton>
            {#if $form.isDirty}
              <PrimaryButton
                type="submit"
                title={aboveMax
                  ? 'Total duration cannot be greater than ' +
                    Duration.toHHMM(log.total_seconds)
                  : 'Save activities'}
                loading={$form.processing}
                disabled={aboveMax ||
                  activitiesTotal == 0 ||
                  log.activities.some((a) => !a.task_category_id)}
              >
                Save
              </PrimaryButton>
            {/if}
          </td>
        </tr>
      </tbody>
    </table>
  {/if}
</form>

<Dialog title="Clock entries" bind:open={clockEntryModalOpen}>
  {#each $clockEntriesForm.entries as entry, index}
    <div class="flex items-center gap-1">
      <input
        bind:value={entry.in_time}
        class="rounded bg-transparent w-44 text-sm"
        type="datetime-local"
      />
      ~
      <input
        bind:value={entry.out_time}
        class="rounded bg-transparent w-44 text-sm"
        type="datetime-local"
      />

      <TimeZoneInfo timezone={entry.timezone} titled />
    </div>
  {/each}

  <div slot="buttons">
    <PrimaryButton on:click={updateClockEntries}>Save</PrimaryButton>
  </div>
</Dialog>
