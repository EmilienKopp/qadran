<script lang="ts">
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import DeleteUserForm from './Partials/DeleteUserForm.svelte';
  import UpdatePasswordForm from './Partials/UpdatePasswordForm.svelte';
  import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.svelte';
  import McpTokenForm from './Partials/McpTokenForm.svelte';
  import { Link } from '@inertiajs/svelte';

  interface Props {
    mustVerifyEmail?: boolean;
    status?: string;
    mcpTokens?: any[];
    mcpConnectionInfo?: any;
    newMcpToken?: any;
    githubStatus?: {
      connected: boolean;
      username?: string | null;
    };
  }

  let {
    mustVerifyEmail = false,
    status = undefined,
    mcpTokens = [],
    mcpConnectionInfo = null,
    newMcpToken = null,
    githubStatus = { connected: false, username: null }
  }: Props = $props();
</script>
<svelte:head>
  <title>Profile</title>
</svelte:head>

<AuthenticatedLayout>
  <div class="pb-12">
    <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
      <div class=" p-4 shadow-sm sm:rounded-lg sm:p-8">
        <UpdateProfileInformationForm
          {mustVerifyEmail}
          {status}
          class="max-w-xl"
        />
      </div>

      <div class=" p-4 shadow-sm sm:rounded-lg sm:p-8">
        <UpdatePasswordForm class="max-w-xl" />
      </div>

      <div class=" p-4 shadow-sm sm:rounded-lg sm:p-8">
        <McpTokenForm 
          class="max-w-4xl" 
          {mcpTokens}
          {mcpConnectionInfo}
          {newMcpToken}
        />
      </div>

      <!-- Voice Assistant Link Card -->
      <div class="p-4 shadow-sm sm:rounded-lg sm:p-8 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
        <div class="max-w-xl">
          <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            AI Voice Assistant
          </h2>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Set up and manage your personal AI voice assistant for hands-free workspace interaction.
          </p>
          <div class="mt-4">
            <Link
              href={route('voice-assistant.show')}
              class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600"
            >
              <svg
                class="h-4 w-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"
                />
              </svg>
              Manage Voice Assistant
            </Link>
          </div>
        </div>
      </div>

      <!-- GitHub Integration Link Card -->
      <div class="p-4 shadow-sm sm:rounded-lg sm:p-8 bg-gradient-to-r from-gray-50 to-slate-50 dark:from-gray-900/20 dark:to-slate-900/20">
        <div class="max-w-xl">
          <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            GitHub Integration
          </h2>
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {#if githubStatus.connected}
              Connected as <span class="font-medium">@{githubStatus.username}</span>. Manage your GitHub connection and access repository data.
            {:else}
              Connect your GitHub account to enable repository access and git log features.
            {/if}
          </p>
          <div class="mt-4 flex items-center gap-3">
            <Link
              href={route('settings.integrations')}
              class="inline-flex items-center gap-2 rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600"
            >
              <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 0C5.374 0 0 5.373 0 12 0 17.302 3.438 21.8 8.207 23.387c.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
              </svg>
              {#if githubStatus.connected}
                Manage GitHub Connection
              {:else}
                Connect GitHub Account
              {/if}
            </Link>
            {#if githubStatus.connected}
              <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">
                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Connected
              </span>
            {/if}
          </div>
        </div>
      </div>

      <div class=" p-4 shadow-sm sm:rounded-lg sm:p-8">
        <DeleteUserForm class="max-w-xl" />
      </div>
    </div>
  </div>
</AuthenticatedLayout>
