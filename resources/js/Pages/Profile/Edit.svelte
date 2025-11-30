<script lang="ts">
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import DeleteUserForm from './Partials/DeleteUserForm.svelte';
  import UpdatePasswordForm from './Partials/UpdatePasswordForm.svelte';
  import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.svelte';
  import McpTokenForm from './Partials/McpTokenForm.svelte';
  import GitHubIntegrationCard from './Partials/GitHubIntegrationCard.svelte';
  import VoiceAssistantCard from './Partials/VoiceAssistantCard.svelte';

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
    githubStatus = { connected: false, username: null },
  }: Props = $props();
</script>

<svelte:head>
  <title>Profile</title>
</svelte:head>

<AuthenticatedLayout>
  <div class="pb-12">
    <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
      <div class="flex gap-6">
        <UpdateProfileInformationForm
          {mustVerifyEmail}
          {status}
          class="max-w-xl"
        />

        <UpdatePasswordForm class="max-w-xl" />
      </div>

      <section class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <GitHubIntegrationCard {githubStatus} />

        <McpTokenForm
          {mcpTokens}
          {mcpConnectionInfo}
          {newMcpToken}
        />

        <VoiceAssistantCard />
      </section>

      <DeleteUserForm class="max-w-xl" />
    </div>
  </div>
</AuthenticatedLayout>
