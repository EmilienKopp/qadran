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
  <span class="countdown font-mono text-2xl">
    <span style="--value:{clock.h};"></span>
    :
    <span style="--value:{clock.m};"></span>
    :
    <span style="--value:{clock.s};"></span>
  </span>
{/if}
