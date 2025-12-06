<script lang="ts">
  import { Duration } from '$lib/utils/duration';
  import { AgCharts } from 'ag-charts-community';
  import dayjs from 'dayjs';
  import duration from 'dayjs/plugin/duration';
  import { onMount } from 'svelte';

  dayjs.extend(duration);

  interface Props {
    data: Record<string, number>;
  }

  let { data = {} }: Props = $props();
  let chartId = `activity_pie_chart_${crypto.randomUUID()}`;

  onMount(() => {
    renderChart();
  });

  function renderChart() {

    console.log("Chart Data",data);

    const options = {
      container: document.getElementById(chartId) as HTMLElement,
      autoSize: true,
      data: data,
      series: [
        {
            type: 'pie',
            angleKey: 'duration_seconds',
            legendItemKey: 'activity_type.name',
            calloutLabelKey: 'duration_seconds',
            calloutLabel: {
                formatter: ({value}: {value: number}) => Duration.toHrMinString(value),
            },
        }
      ]
    };

    AgCharts.create(options);
  }

</script>

<div id={chartId} class="w-full h-64">
  <!-- Chart will be rendered here -->
</div>
