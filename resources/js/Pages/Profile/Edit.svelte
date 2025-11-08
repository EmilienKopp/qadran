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
  }

  let { 
    mustVerifyEmail = false, 
    status = undefined,
    mcpTokens = [],
    mcpConnectionInfo = null,
    newMcpToken = null
  }: Props = $props();
</script>
<svelte:head>
  <title>Profile</title>
</svelte:head>

<AuthenticatedLayout>
  {#snippet header()}
  
      <h2 class="text-xl font-semibold leading-tight text-gray-800 bg-base-100">Profile</h2>
    
  {/snippet}

  <div class="py-12">
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

      <div class=" p-4 shadow-sm sm:rounded-lg sm:p-8">
        <DeleteUserForm class="max-w-xl" />
      </div>
    </div>
  </div>
</AuthenticatedLayout>
