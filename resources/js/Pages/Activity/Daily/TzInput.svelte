<script lang="ts">
  import type { Timezone } from '$types/index';
  import dayjs from 'dayjs';
  import timezone from 'dayjs/plugin/timezone';
  import utc from 'dayjs/plugin/utc';

  dayjs.extend(timezone);
  dayjs.extend(utc);

  export let tz: Timezone = 'UTC';
  export let value: string | number | Date = dayjs().format(
    'YYYY-MM-DDTHH:mm:ss'
  );
</script>

<div class="flex items-center">
  <input bind:value class="rounded bg-transparent" type="datetime-local" />
</div>
