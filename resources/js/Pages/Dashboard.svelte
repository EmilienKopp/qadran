<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import Select from '$components/DataInput/Select.svelte';
  import EntriesList from '$components/Entries/EntriesList.svelte';
  import Clock from '$components/UI/Clock.svelte';
  import Terminal from '$components/Terminal.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import { ClockEntry } from '$lib/domain/ClockEntry';
  import { User } from '$lib/domain/User/index.svelte';
  import { superUseForm } from '$lib/inertia';
  import { asSelectOptions } from '$lib/utils/formatting';
  import { getTimezone } from '$lib/utils/timezone';
  import type { Project } from '$models';

  interface Props {
    user: User;
  }

  let { user }: Props = $props();
  let latestEntry = $derived(user?.todays_entries?.[0]);
  let projectOptions = asSelectOptions<Project>(user.projects, 'id', 'name');

  let form = superUseForm({
    project_id: latestEntry?.project_id,
    timezone: getTimezone(),
  });

  let nextAction = $derived.by(() => ClockEntry.determineNextAction($form,latestEntry));

</script>

<svelte:head>
  <title>Dashboard</title>
</svelte:head>

<AuthenticatedLayout>
  <div class="py-12 w-full">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 w-full">
      <div class="overflow-hidden shadow-xs sm:rounded-lg w-full">
        <div
          class="p-6 text-gray-900 flex flex-col items-center justify-between w-full"
        >
          <fieldset class="p-4 flex items-center justify-center gap-8 border rounded-md">
            <legend>
              Punch
            </legend>
            <Select
              bind:value={$form.project_id}
              options={projectOptions}
            />
            <Button disabled={!$form.project_id} onclick={() => ClockEntry.push($form)}>
              {nextAction}
            </Button>
          </fieldset>
          <div class="w-full mt-4 flex flex-col items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 mt-8">
              Today's Entries
            </h2>
            <EntriesList entries={user.todays_entries} />
          </div>
        </div>
      </div>
    </div>
  </div>
</AuthenticatedLayout>
