<script lang="ts">
  interface Props {
    class?: string;
  }

  let { class: className = '' }: Props = $props();
</script>

<div class={className}>
  <h3 class="text-2xl font-bold mb-4">MCP Integration</h3>
  
  <div class="space-y-4">
    <p class="text-base-content/80">
      Built with Model Context Protocol support, enabling AI assistants like Claude to interact 
      directly with your workspace. Automate workflows, generate reports, and query your data using 
      natural language.
    </p>

    <div class="space-y-3">
      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">AI Assistant Integration</p>
          <p class="text-sm text-base-content/60">Connect Claude and other MCP-compatible AI tools to your workspace</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Natural Language Queries</p>
          <p class="text-sm text-base-content/60">Ask questions about your time data in plain English</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Workflow Automation</p>
          <p class="text-sm text-base-content/60">Automate repetitive tasks through AI-powered commands</p>
        </div>
      </div>

      <div class="flex gap-3 items-start">
        <div class="badge badge-neutral mt-1">✓</div>
        <div>
          <p class="font-semibold">Secure Authentication</p>
          <p class="text-sm text-base-content/60">Tenant-aware authentication ensures data privacy and security</p>
        </div>
      </div>
    </div>

    <div class="alert">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
      <span class="text-sm">MCP is an open protocol for connecting AI assistants to external tools and data sources.</span>
    </div>
  </div>
</div>
