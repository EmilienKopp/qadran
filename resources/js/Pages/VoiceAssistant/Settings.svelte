<script lang="ts">
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import VoiceAssistantStatus from './Partials/VoiceAssistantStatus.svelte';
  import VoiceAssistantPreferences from './Partials/VoiceAssistantPreferences.svelte';
  import VoiceAssistantActivation from './Partials/VoiceAssistantActivation.svelte';

  interface Props {
    config?: N8nConfig | null;
    isActivated?: boolean;
    canActivate?: boolean;
    webhookUrl?: string | null;
  }

  interface N8nConfig {
    ai_credentials?: {
      id: string;
      name: string;
    } | null;
    mcp_credentials?: {
      id: string;
      name: string;
    } | null;
    mcp_endpoint_url?: string | null;
    workflow_id?: string | null;
    webhook_url?: string | null;
    preferences?: Record<string, any> | null;
  }

  let { 
    config = null,
    isActivated = false,
    canActivate = false,
    webhookUrl = null
  }: Props = $props();
</script>

<svelte:head>
  <title>AI Voice Assistant</title>
</svelte:head>

<AuthenticatedLayout>
  {#snippet header()}
    <h2 class="text-xl font-semibold leading-tight  dark:">
      AI Voice Assistant
    </h2>
  {/snippet}

  <div class="py-12">
    <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
      <!-- Status Card -->
      <div class="bg-white p-4 shadow-sm dark:bg-gray-800 sm:rounded-lg sm:p-8">
        <VoiceAssistantStatus 
          {isActivated}
          {config}
          {webhookUrl}
        />
      </div>

      <!-- Activation/Deactivation Card -->
      {#if !isActivated}
        <div class="bg-white p-4 shadow-sm dark:bg-gray-800 sm:rounded-lg sm:p-8">
          <VoiceAssistantActivation {canActivate} />
        </div>
      {:else}
        <!-- Preferences Card (only shown when activated) -->
        <div class="bg-white p-4 shadow-sm dark:bg-gray-800 sm:rounded-lg sm:p-8">
          <VoiceAssistantPreferences preferences={config?.preferences} />
        </div>
      {/if}
    </div>
  </div>
</AuthenticatedLayout>
