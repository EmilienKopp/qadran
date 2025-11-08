<script lang="ts">
  interface Props {
    isActivated: boolean;
    config?: N8nConfig | null;
    webhookUrl?: string | null;
  }

  interface N8nConfig {
    workflow_id?: string | null;
    webhook_url?: string | null;
    mcp_endpoint_url?: string | null;
    preferences?: Record<string, any> | null;
  }

  let { isActivated, config = null, webhookUrl = null }: Props = $props();

  let copied = $state(false);

  function copyWebhookUrl() {
    const url = webhookUrl || config?.webhook_url;
    if (url) {
      navigator.clipboard.writeText(url);
      copied = true;
      setTimeout(() => (copied = false), 2000);
    }
  }

  const statusBadge = $derived(
    isActivated
      ? { text: 'Active', class: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' }
      : { text: 'Inactive', class: 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }
  );

  const effectiveWebhookUrl = $derived(webhookUrl || config?.webhook_url);
</script>

<section class="space-y-6">
  <header>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
      Voice Assistant Status
    </h2>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
      View your AI voice assistant configuration and status.
    </p>
  </header>

  <div class="space-y-4">
    <!-- Status Badge -->
    <div class="flex items-center gap-3">
      <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Status:</span>
      <span
        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {statusBadge.class}"
      >
        {statusBadge.text}
      </span>
    </div>

    {#if isActivated && config}
      <!-- Workflow ID -->
      {#if config.workflow_id}
        <div class="flex flex-col gap-1">
          <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
            Workflow ID:
          </span>
          <code
            class="rounded bg-gray-100 px-2 py-1 text-sm text-gray-800 dark:bg-gray-700 dark:text-gray-200"
          >
            {config.workflow_id}
          </code>
        </div>
      {/if}

      <!-- Webhook URL -->
      {#if effectiveWebhookUrl}
        <div class="flex flex-col gap-1">
          <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
            Webhook URL:
          </span>
          <div class="flex items-center gap-2">
            <code
              class="flex-1 rounded bg-gray-100 px-2 py-1 text-sm text-gray-800 dark:bg-gray-700 dark:text-gray-200"
            >
              {effectiveWebhookUrl}
            </code>
            <button
              type="button"
              onclick={copyWebhookUrl}
              class="rounded bg-gray-200 px-3 py-1 text-sm font-medium text-gray-700 hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500"
            >
              {copied ? 'Copied!' : 'Copy'}
            </button>
          </div>
        </div>
      {/if}

      <!-- MCP Endpoint -->
      {#if config.mcp_endpoint_url}
        <div class="flex flex-col gap-1">
          <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
            MCP Endpoint:
          </span>
          <code
            class="rounded bg-gray-100 px-2 py-1 text-sm text-gray-800 dark:bg-gray-700 dark:text-gray-200"
          >
            {config.mcp_endpoint_url}
          </code>
        </div>
      {/if}
    {/if}
  </div>
</section>
