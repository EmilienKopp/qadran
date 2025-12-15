<script lang="ts">
  import { clock } from '$lib/stores/global/time.svelte';

  $effect(() => {
    const interval = setInterval(() => {
      clock.refresh();
    }, 1000);

    return () => clearInterval(interval);
  });
</script>

{#if clock.time}
  <div class="countdown font-mono text-2xl w-36">
    <span style="--value:{clock.h}; --digits: 2;" aria-live="polite" aria-label={clock.h}>{clock.h}</span>
    :
    <span style="--value:{clock.m}; --digits: 2;" aria-live="polite" aria-label={clock.m}>{clock.m}</span>
    :
    <span style="--value:{clock.s}; --digits: 2;" aria-live="polite" aria-label={clock.s}>{clock.s}</span>
  </div>
{/if}
