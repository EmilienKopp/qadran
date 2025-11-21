<script lang="ts">
  interface Props {
    class?: string;
  }

  let { class: className = '' }: Props = $props();
</script>

<div class={className}>
  <h3 class="text-2xl font-bold mb-4">Reports & Analytics</h3>
  
  <div class="space-y-4">
    <p class="text-base-content/80">
      Transform your time data into actionable insights. Generate comprehensive reports with detailed 
      breakdowns, visualizations, and metrics that help you understand productivity patterns and 
      optimize your workflow.
    </p>

    <div class="space-y-3">
      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Comprehensive Time Reports</p>
          <p class="text-sm text-base-content/60">Generate detailed reports by project, date range, or organization</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Export Options</p>
          <p class="text-sm text-base-content/60">Export reports in multiple formats for invoicing and record-keeping</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Team Analytics</p>
          <p class="text-sm text-base-content/60">Track team productivity and identify bottlenecks across projects</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Custom Date Ranges</p>
          <p class="text-sm text-base-content/60">Flexible reporting periods from daily summaries to yearly overviews</p>
        </div>
      </div>
    </div>
  </div>
</div>
