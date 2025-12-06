<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import MiniButton from '$components/Buttons/MiniButton.svelte';
  import { leftPad } from '$lib/utils/strings';
  import { router } from '@inertiajs/svelte';
  import dayjs from 'dayjs';
  import localeData from 'dayjs/plugin/localeData';
  import { onMount } from 'svelte';
  import DefaultActivityInlineReport from '$components/Activity/ActivityInlineReport.svelte';

  dayjs.extend(localeData);

  interface Props {
    headers?: string[];
    data?: Array<any>;
    date?: Date | string;
    ActivityInlineReport?: any; // Component for inline activity reports
    routePrefix?: string; // For generating routes dynamically
  }

  let {
    headers = [],
    data = [],
    date = new Date(),
    ActivityInlineReport = DefaultActivityInlineReport,
    routePrefix = 'activities',
  }: Props = $props();

  const latestDateWithLogs = Object.keys(data).sort().reverse()[0];

  let detailsOpen = $state(Array.from({ length: 31 }, () => false));
  let container = $state<HTMLDivElement | undefined>();
  let latestNonEmptyRow = $state<HTMLTableRowElement | undefined>();
  let scrollY = $state(0);

  const allOpen = $derived(detailsOpen.every((b) => b));

  onMount(() => {
    scrollToLatest();
  });

  function scrollToLatest() {
    if (latestDateWithLogs) {
      latestNonEmptyRow = document.getElementById(
        latestDateWithLogs
      ) as HTMLTableRowElement;
      latestNonEmptyRow?.scrollIntoView({
        behavior: 'smooth',
        block: 'center',
      });
    }
  }

  function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function toggleDetails(index: number) {
    detailsOpen[index] = !detailsOpen[index];
  }

  function toggleAllDetails() {
    detailsOpen = Array.from({ length: 31 }, () => !allOpen);
  }

  function handleContextMenu(event: MouseEvent) {
    console.log(event);
    const X = event.clientX;
    const Y = event.clientY;
    console.log(X, Y);
  }

  const year = $derived(dayjs(date).format('YYYY'));
  const month = $derived(dayjs(date).format('MM'));
  const readableMonth = $derived(dayjs(date).format('MMMM'));
  const groupedData = $derived(
    Object.groupBy(data, (log: any) => {
      return dayjs(log.date).format('YYYY-MM-DD');
    }) as Record<string, any[]>
  );
  $inspect(data, groupedData);

  const dateMap = $derived(
    Object.fromEntries(
      Array.from({ length: dayjs(date).daysInMonth() }, (_, i) => {
        let day = i + 1;
        let dateStr = `${year}-${month}-${leftPad(day, '0', 2)}`;
        return [dateStr, groupedData[dateStr]];
      })
    )
  );

  const datesArray = $derived(Object.entries(dateMap));
</script>

<svelte:window bind:scrollY />

<div class="flex flex-col gap-4">
  <header class="flex justify-between items-center px-4">
    <Button
      variant="secondary"
      href={`/${routePrefix}/index?date=${dayjs(date).subtract(1, 'month').format('YYYY-MM-DD')}`}
    >
      Back
    </Button>
    <h2 class="text-3xl w-full text-center font-bold">
      {readableMonth}
      {year}
    </h2>
    <Button
      variant="secondary"
      href={`/${routePrefix}/index?date=${dayjs(date).add(1, 'month').format('YYYY-MM-DD')}`}
    >
      Next
    </Button>
  </header>

  <div class="w-11/12 pb-10 mx-auto shadow-sm flex gap-2" bind:this={container}>
    <table
      class="table table-hover w-full table-xs table-pin-rows table-pin-cols"
    >
      <thead class="z-50">
        <tr>
          <th>Date</th>
          <th class="flex items-center justify-between">
            <span>Activities</span>
            {#if scrollY > 125}
              <div class="">{readableMonth} {year}</div>
            {/if}
            <div class="flex gap-2">
              <MiniButton variant="accent" onclick={scrollToTop}
                >To Top</MiniButton
              >
              <MiniButton variant="accent" onclick={scrollToLatest}
                >See Latest</MiniButton
              >
              <MiniButton variant="accent" onclick={toggleAllDetails}>
                {allOpen ? 'Close' : 'Open'} All
              </MiniButton>
            </div>
          </th>
        </tr>
      </thead>
      <tbody>
        {#each datesArray as [key, logs], index}
          <tr
            id={key}
            class="hover"
            oncontextmenu={(e) => {
              e.preventDefault();
              handleContextMenu(e);
            }}
          >
            <td>
              <div class=" text-center">
                {dayjs(key).date()}<br />
                ({dayjs.weekdaysShort()[dayjs(key).day()]})
              </div>
            </td>
            {#if logs?.length}
              <td>
                <div
                  class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 w-full h-full gap-2"
                >
                  {#if ActivityInlineReport}
                    {#each logs as log, logIndex}
                      <ActivityInlineReport
                        id={`activity_${key.replaceAll('-', '')}${index}${logIndex}`}
                        {log}
                        detailsOpen={detailsOpen[index]}
                      />
                    {/each}
                  {:else}
                    {#each logs as log}
                      <div class="p-2 border rounded">
                        {JSON.stringify(log)}
                      </div>
                    {/each}
                  {/if}

                  <div
                    class="flex items-center gap-1 self-center justify-self-end"
                    style="grid-column: -1 / auto"
                  >
                    <MiniButton
                      variant="primary"
                      href={`/${routePrefix}/show?date=${key}`}
                    >
                      Edit
                    </MiniButton>
                    {#if detailsOpen[index]}
                      <MiniButton
                        variant="accent"
                        onclick={() => toggleDetails(index)}
                      >
                        Hide
                      </MiniButton>
                    {:else}
                      <MiniButton
                        variant="accent"
                        onclick={() => toggleDetails(index)}
                      >
                        Details
                      </MiniButton>
                    {/if}
                  </div>
                </div>
              </td>
            {:else}
              <td
                onclick={() => router.visit(`/${routePrefix}/show?date=${key}`)}
                class="cursor-pointer hover:bg-base-200"
              >
                <div
                  class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 w-full h-full"
                >
                  <MiniButton
                    variant="secondary"
                    class="col-start-3 place-self-end"
                  >
                    Add
                  </MiniButton>
                </div>
              </td>
            {/if}
          </tr>
        {/each}
      </tbody>
    </table>
  </div>
</div>
