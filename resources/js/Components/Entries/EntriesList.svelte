<script lang="ts">
  import { toaster } from '$components/Feedback/Toast/ToastHandler.svelte';
  import Clock from '$components/UI/Clock.svelte';
  import { ClockEntry } from '$lib/domain/ClockEntry';
  import { secondsToHHMM, time } from '$lib/utils/formatting';
  import { router } from '@inertiajs/svelte';
  import dayjs from 'dayjs';
  import { X } from 'lucide-svelte';
  import { fade } from 'svelte/transition';

  interface Props {
    entries?: ClockEntry[];
  }

  let { entries }: Props = $props();

  function handleDelete(entry: ClockEntry) {
    const index = entries?.findIndex((e) => e.id === entry.id);
    if(index === -1 || index === undefined) return;
    entries = entries?.toSpliced(index, 1);
    router.delete(route('clock-entry.destroy', entry.id),{
      onSuccess: () => {
        toaster.success('Entry deleted');
      },
      onError: ({error}) => {
        toaster.error(error);
        entries = entries?.toSpliced(index, 0, entry);
      },
    });
  }
</script>

{#snippet listItem(entry: ClockEntry)}
  <li class="flex flex-row space-x-2" in:fade>
    <div>{entry.project?.name || 'No Project'}</div>
    <div>{time(entry.in)}</div>
    <div>{time(entry.out) || '-'}</div>
    <div>{secondsToHHMM(entry.duration_seconds)}</div>
    <button class="text-red-500" onclick={() => handleDelete(entry)}>
      <X size={16} />
    </button>
  </li>
{/snippet}

<ul>
  {#if !entries?.length}
    <li>No entries yet</li>
  {:else}
    {#each entries! as entry}
      {@render listItem(entry)}
    {/each}
  {/if}
</ul>
