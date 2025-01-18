<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import Select from '$components/DataInput/Select.svelte';
  import EntriesList from '$components/Entries/EntriesList.svelte';
  import Clock from '$components/UI/Clock.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import { ClockEntry } from '$lib/domain/ClockEntry';
  import { User } from '$lib/domain/User/index.svelte';
  import { superUseForm } from '$lib/inertia';
  import { asSelectOptions } from '$lib/utils/formatting';
  import { getTimezone } from '$lib/utils/timezone';
  import type { Project } from '$models';
  import dayjs from 'dayjs';

  

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

  $inspect($form);
</script>

<svelte:head>
  <title>Dashboard</title>
</svelte:head>

<AuthenticatedLayout>
  {#snippet header()}
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      <Clock />
    </h2>
  {/snippet}

  <div class="py-12 w-full">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 w-full">
      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg w-full">
        <div
          class="p-6 text-gray-900 flex flex-col items-center justify-between w-full"
        >
          <Select
            label="Select a project"
            bind:value={$form.project_id}
            options={projectOptions}
          />
          <Button onclick={() => ClockEntry.push($form)} class="mt-4">
            Submit
          </Button>
          <div>
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
