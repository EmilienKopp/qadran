<script lang="ts">
  interface Props {
    class?: string;
  }

  let { class: className = '' }: Props = $props();
</script>

<div class={className}>
  <h3 class="text-2xl font-bold mb-4">Tailored for Developers</h3>
  
  <div class="space-y-4">
    <p class="text-base-content/80">
      Built by developers, for developers. Seamlessly integrate your GitHub repositories to 
      automatically generate detailed reports from your commit history, eliminating manual time logging.
    </p>

    <div class="space-y-3">
      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">GitHub Integration</p>
          <p class="text-sm text-base-content/60">Connect your repositories and pull commit data automatically</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Automated Report Generation</p>
          <p class="text-sm text-base-content/60">Generate timesheet reports directly from your commit history</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Commit Analysis</p>
          <p class="text-sm text-base-content/60">Analyze your work patterns and productivity through code contributions</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Project Mapping</p>
          <p class="text-sm text-base-content/60">Automatically associate commits with projects and time entries</p>
        </div>
      </div>
    </div>
  </div>
</div>
