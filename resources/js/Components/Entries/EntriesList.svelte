<script lang="ts">
  import OutlineButton from '$components/Buttons/OutlineButton.svelte';
  import DeleteButton from '$components/Display/DeleteButton.svelte';
  import { toaster } from '$components/Feedback/Toast/ToastHandler.svelte';
  import { ClockEntry } from '$lib/domain/ClockEntry';
  import { clock } from '$lib/stores/global/time.svelte';
  import { time, timespan } from '$lib/utils/formatting';
  import { Link, router } from '@inertiajs/svelte';
  import dayjs from 'dayjs';
  import { fade } from 'svelte/transition';

  interface Props {
    entries?: ClockEntry[];
  }

  let { entries }: Props = $props();

  function handleDelete(entry: ClockEntry) {
    const index = entries?.findIndex((e) => e.id === entry.id);
    if (index === -1 || index === undefined) return;
    entries = entries?.toSpliced(index, 1);
    router.delete(route('clock-entry.destroy', entry.id), {
      onSuccess: () => {
        toaster.success('Entry deleted');
      },
      onError: ({ error }) => {
        toaster.error(error);
        entries = entries?.toSpliced(index, 0, entry);
      },
    });
  }
</script>

{#snippet listItem(entry: ClockEntry)}
  <tr in:fade>
    <td>
      {#if entry.project}
        <a href={route('project.show', entry.project.id)} class="text-blue-500">
          {entry.project.name}
        </a>
      {:else}
        No project
      {/if}
    </td>
    <td>{time(entry.in)}</td>
    <td>{time(entry.out) || '-'}</td>
    <td class="w-32">
        {entry.out ? timespan(entry.in, entry.out) : clock.since(entry.in)}
    </td>
    <td>
        <OutlineButton viewTransition class="mr-2" href={route('activities.show', dayjs(entry.in).format('YYYY-MM-DD'))}>
          See Details
        </OutlineButton>
      <DeleteButton class="text-red-500" onclick={() => handleDelete(entry)} />
    </td>
  </tr>
{/snippet}

<table class="w-full">
  <thead>
    <tr>
      <th>Project</th>
      <th>In</th>
      <th>Out</th>
      <th>Duration</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    {#if !entries?.length}
      <tr>
        <td colspan="5">No entries yet</td>
      </tr>
    {:else}
      {#each entries! as entry}
        {@render listItem(entry)}
      {/each}
    {/if}
  </tbody>
</table>

<style>
  table {
    border-collapse: collapse;
    width: 100%;
  }

  th,
  td {
    border: 1px solid #e2e8f0;
    padding: 0.5rem;
    text-align: center;
  }

  th {
    background-color: #f7fafc;
  }

  tr {
    transition: background-color 0.2s;
  }

  tr:hover {
    background-color: #f7fafc;
  }

  button {
    cursor: pointer;
  }
</style>