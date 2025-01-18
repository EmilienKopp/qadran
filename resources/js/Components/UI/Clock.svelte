<script lang="ts">
  import dayjs from "dayjs";

  let time = $state(dayjs().format('HH:mm:ss'));

  $effect(() => {
    const interval = setInterval(() => {
      time = dayjs().format('HH:mm:ss');
    }, 1000);

    return () => clearInterval(interval);
  })
</script>

{#if time}
  <div class="text-2xl font-bold text-center">
    {time}
  </div>
{/if}