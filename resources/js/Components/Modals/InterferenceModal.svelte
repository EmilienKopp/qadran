<script lang="ts">
  import Dialog from './Dialog.svelte';
  import Input from '$components/DataInput/Input.svelte';
  import Select from '$components/DataInput/Select.svelte';
  import Textarea from '$components/DataInput/Textarea.svelte';
  import MiniButton from '$components/Buttons/MiniButton.svelte';
  import { superUseForm } from '$lib/inertia';
  import { asSelectOptions } from '$lib/utils/formatting';
  import { getTimezone } from '$lib/utils/timezone';
  import type { Project } from '$models';

  interface Props {
    open?: boolean;
    projects: Project[];
    clockEntryId?: number | null;
  }

  let { open = $bindable(false), projects, clockEntryId = null }: Props = $props();

  let projectOptions = asSelectOptions<Project>(projects, 'id', 'name');

  // Get current time for default values
  const now = new Date();
  const currentTime = now.toTimeString().slice(0, 5); // HH:MM format
  
  let form = superUseForm({
    in: currentTime,
    out: currentTime,
    project_id: null as number | null,
    clock_entry_id: clockEntryId,
    notes: '',
    timezone: getTimezone(),
  });

  function handleSubmit() {
    $form.post(route('interferences.store'), {
      onSuccess: () => {
        open = false;
        // Reset form
        $form.in = currentTime;
        $form.out = currentTime;
        $form.project_id = null;
        $form.notes = '';
      },
    });
  }
</script>

<Dialog
  bind:open
  title="Register Interference"
  class="max-w-md"
  onsubmit={handleSubmit}
>
  {#snippet children()}
    <div class="flex flex-col gap-4">
      <p class="text-sm text-gray-600">
        Record a brief interruption during your current work session.
      </p>
      
      <Input
        label="Start Time"
        type="time"
        bind:value={$form.in}
        error={$form.errors.in}
        required
      />
      
      <Input
        label="End Time"
        type="time"
        bind:value={$form.out}
        error={$form.errors.out}
        required
      />
      
      <Select
        label="Project"
        bind:value={$form.project_id}
        options={projectOptions}
        error={$form.errors.project_id}
      />
      
      <Textarea
        label="Notes"
        bind:value={$form.notes}
        error={$form.errors.notes}
        placeholder="Brief description of the interruption..."
      />
    </div>
  {/snippet}

  {#snippet buttons()}
    <div class="flex gap-2 justify-end mt-4">
      <MiniButton
        color="ghost"
        type="button"
        onclick={() => (open = false)}
      >
        Cancel
      </MiniButton>
      <MiniButton
        color="primary"
        type="submit"
        disabled={$form.processing}
      >
        {$form.processing ? 'Saving...' : 'Register Interference'}
      </MiniButton>
    </div>
  {/snippet}
</Dialog>
