<script lang="ts">
  import { Globe } from '@lucide/svelte';
  import { listTimezones, getTimezone } from '$lib/utils/timezone';
  import voiceCommands from '$lib/stores/global/voiceCommands.svelte';
  import Select from '$components/DataInput/Select.svelte';

  let timezones: string[] = listTimezones();
  let selectedTimezone: string = getTimezone();
</script>

<div>
  <div class="dropdown dropdown-end">
    <div class="tooltip tooltip-left" data-tip={selectedTimezone}>
      <div tabindex="0" role="button" class="btn btn-sm btn-ghost m-1 mx-3">
        <Globe />
      </div>
    </div>
    <div
      tabindex="-1"
      class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm"
    >
      <Select
        bind:value={selectedTimezone}
        options={timezones.map((tz) => ({ value: tz, name: tz }))}
        label="Select Timezone"
      />
    </div>
  </div>
</div>
