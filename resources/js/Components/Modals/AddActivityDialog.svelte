<script lang="ts">
  import PrimaryButton from "$components/Buttons/PrimaryButton.svelte";
  import OutlineButton from "$components/Buttons/OutlineButton.svelte";
  import InputLabel from "$components/DataInput/InputLabel.svelte";
  import Select from "$components/DataInput/Select.svelte";
  import { toast } from "$lib/stores";
  import { useForm } from "@inertiajs/svelte";
  import Dialog from "./Dialog.svelte";
  import type { ActivityType, Task } from "$models";
  import dayjs from 'dayjs';

  interface Props {
    open?: boolean;
    clockEntryId: number;
    clockEntryStartTime: string;
    activityTypes?: ActivityType[];
    tasks?: Task[];
  }

  let {
    open = $bindable(false),
    clockEntryId,
    clockEntryStartTime,
    activityTypes = [],
    tasks = [],
  }: Props = $props();

  let startTime = $state('');
  let endTime = $state('');

  const form = useForm({
    clock_entry_id: clockEntryId,
    activity_type_id: null as number | null,
    task_id: null as number | null,
    start_offset_seconds: 0,
    end_offset_seconds: null as number | null,
    duration_seconds: null as number | null,
    notes: '',
  });

  function calculateOffsets() {
    if (startTime && endTime) {
      const clockStart = dayjs(clockEntryStartTime);
      const activityStart = dayjs(`${clockStart.format('YYYY-MM-DD')} ${startTime}`);
      const activityEnd = dayjs(`${clockStart.format('YYYY-MM-DD')} ${endTime}`);

      $form.start_offset_seconds = activityStart.diff(clockStart, 'second');
      $form.end_offset_seconds = activityEnd.diff(clockStart, 'second');
      $form.duration_seconds = activityEnd.diff(activityStart, 'second');
    }
  }

  function handleSubmit() {
    calculateOffsets();

    $form.post(route('activity-logs.store'), {
      onSuccess: () => {
        open = false;
        toast.success('Activity added successfully.');
        $form.reset();
        startTime = '';
        endTime = '';
      },
      onError: () => {
        toast.error('Error adding activity.');
        console.log($form.errors);
      }
    });
  }

  function handleCancel() {
    open = false;
    $form.reset();
    startTime = '';
    endTime = '';
  }

  $effect(() => {
    $form.clock_entry_id = clockEntryId;
  });
</script>

<Dialog title="Add Activity" onsubmit={handleSubmit} bind:open>
  <div class="flex flex-col gap-4">
    <div class="flex flex-col gap-2">
      <InputLabel value="Activity Type" for="activity_type_id">
        <Select
          id="activity_type_id"
          name="activity_type_id"
          bind:value={$form.activity_type_id}
        >
          <option value="">Select activity type</option>
          {#each activityTypes as activityType}
            <option value={activityType.id}>{activityType.name}</option>
          {/each}
        </Select>
      </InputLabel>
      {#if $form.errors.activity_type_id}
        <p class="text-red-500 text-sm">{$form.errors.activity_type_id}</p>
      {/if}
    </div>

    <div class="grid grid-cols-2 gap-4">
      <div class="flex flex-col gap-2">
        <InputLabel value="Start Time" for="start_time">
          <input
            class="bg-base-100 form-control"
            type="time"
            id="start_time"
            name="start_time"
            bind:value={startTime}
          />
        </InputLabel>
      </div>

      <div class="flex flex-col gap-2">
        <InputLabel value="End Time" for="end_time">
          <input
            class="bg-base-100 form-control"
            type="time"
            id="end_time"
            name="end_time"
            bind:value={endTime}
          />
        </InputLabel>
      </div>
    </div>

    <div class="flex flex-col gap-2">
      <InputLabel value="Task (Optional)" for="task_id">
        <Select
          id="task_id"
          name="task_id"
          bind:value={$form.task_id}
        >
          <option value="">No task</option>
          {#each tasks as task}
            <option value={task.id}>{task.name}</option>
          {/each}
        </Select>
      </InputLabel>
      {#if $form.errors.task_id}
        <p class="text-red-500 text-sm">{$form.errors.task_id}</p>
      {/if}
    </div>

    <div class="flex flex-col gap-2">
      <InputLabel value="Notes (Optional)" for="notes">
        <textarea
          class="bg-base-100 form-control"
          id="notes"
          name="notes"
          bind:value={$form.notes}
          rows="3"
        ></textarea>
      </InputLabel>
      {#if $form.errors.notes}
        <p class="text-red-500 text-sm">{$form.errors.notes}</p>
      {/if}
    </div>

    <div class="grid grid-cols-2 gap-4 pt-4">
      <OutlineButton type="button" onclick={handleCancel}>Cancel</OutlineButton>
      <PrimaryButton type="submit" loading={$form.processing}>Add Activity</PrimaryButton>
    </div>
  </div>
</Dialog>
