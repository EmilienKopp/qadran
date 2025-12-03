<script lang="ts">
  import { Globe } from '@lucide/svelte';
  import { listTimezones, getTimezone } from '$lib/utils/timezone';
  import { Form } from '@inertiajs/svelte';
  import MiniButton from '$components/Buttons/MiniButton.svelte';
  import Svelecte from 'svelecte';
  import { shared } from '$lib/inertia';

  let timezones: string[] = $state(listTimezones());
  let selectedTimezone: string = $state(shared('timezone') ?? getTimezone());
</script>

<Form method="post" action={route('user.timezone.update')}>
  {#snippet children({ processing }: any)}
    <div class="dropdown dropdown-end">
      <div class="tooltip tooltip-left" data-tip={selectedTimezone}>
        <div tabindex="0" role="button" class="btn btn-sm btn-ghost m-1 mx-1">
          <Globe class="size-4" />
        </div>
      </div>
      <div
        tabindex="-1"
        class="dropdown-content menu bg-base-100 rounded-box w-52 p-2 shadow-sm z-[999]"
      >
        <Svelecte
          bind:value={selectedTimezone}
          options={timezones.map((tz) => ({ value: tz, text: tz }))}
          name="timezone"
        />
        <MiniButton type="submit" class="mt-2 w-full" {processing}>
          Save as preference
        </MiniButton>
      </div>
    </div>
  {/snippet}
</Form>
