<script lang="ts">
  import { ClockEntry } from '$lib/domain/ClockEntry';
  import { time } from '$lib/utils/formatting';

  interface Props {
    entries: ClockEntry[];
  }

  let { entries }: Props = $props();
</script>

{#snippet listItem(entry: ClockEntry)}
  <li class="flex flex-row space-x-2">
    <div>{entry.project?.name || 'No Project'}</div>
    <div>{time(entry.in)}</div>
    <div>{time(entry.out) || '-'}</div>
  </li>
{/snippet}

<ul>

  {#each entries as entry}
    {@render listItem(entry)}
  {/each}
</ul>
