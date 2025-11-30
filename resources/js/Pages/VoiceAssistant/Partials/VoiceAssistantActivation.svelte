<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import Modal from '$components/Actions/Modal.svelte';
  import { router } from '@inertiajs/svelte';

  interface Props {
    canActivate: boolean;
  }

  let { canActivate }: Props = $props();

  let activateModal: { showModal: () => void; close: () => void };
  let processing = $state(false);

  function openActivateModal() {
    activateModal?.showModal();
  }

  function closeActivateModal() {
    activateModal?.close();
  }

  function activateAssistant() {
    processing = true;
    router.post(
      route('voice-assistant.activate'),
      {},
      {
        preserveScroll: true,
        onSuccess: () => {
          closeActivateModal();
          processing = false;
        },
        onError: () => {
          processing = false;
        },
      }
    );
  }
</script>

<section class="space-y-6">
  <header>
    <h2 class="text-lg font-medium  dark:">
      Activate AI Voice Assistant
    </h2>
    <p class="mt-1 text-sm  dark:">
      Set up your personal AI voice assistant to interact with your workspace.
    </p>
  </header>

  {#if !canActivate}
    <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4 dark:border-yellow-900 dark:bg-yellow-900/20">
      <div class="flex items-start gap-3">
        <svg
          class="h-5 w-5 text-yellow-600 dark:text-yellow-500"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
          />
        </svg>
        <div>
          <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
            Configuration Required
          </h3>
          <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">
            The AI Voice Assistant needs to be configured at the tenant level before you can activate it.
            Please contact your administrator to set up the base configuration.
          </p>
        </div>
      </div>
    </div>
  {:else}
    <div class="space-y-4">
      <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-900 dark:bg-blue-900/20">
        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
          What happens when you activate?
        </h3>
        <ul class="mt-2 space-y-1 text-sm text-blue-700 dark:text-blue-300">
          <li class="flex items-start gap-2">
            <svg
              class="mt-0.5 h-4 w-4 flex-shrink-0"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"
              />
            </svg>
            <span>A Personal Access Token (PAT) will be created for secure MCP authentication</span>
          </li>
          <li class="flex items-start gap-2">
            <svg
              class="mt-0.5 h-4 w-4 flex-shrink-0"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"
              />
            </svg>
            <span>MCP credentials will be registered in n8n</span>
          </li>
          <li class="flex items-start gap-2">
            <svg
              class="mt-0.5 h-4 w-4 flex-shrink-0"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"
              />
            </svg>
            <span>An AI agent workflow will be created and linked to your account</span>
          </li>
          <li class="flex items-start gap-2">
            <svg
              class="mt-0.5 h-4 w-4 flex-shrink-0"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"
              />
            </svg>
            <span>You'll receive a webhook URL to start using voice commands</span>
          </li>
        </ul>
      </div>

      <Button onclick={openActivateModal}>
        Activate AI Voice Assistant
      </Button>
    </div>
  {/if}
</section>

<!-- Activation Confirmation Modal -->
<Modal bind:this={activateModal}>
  <div class="p-6">
    <h3 class="text-lg font-medium  dark:">
      Activate AI Voice Assistant
    </h3>
    <p class="mt-2 text-sm  dark:">
      This will create the necessary credentials and workflow for your AI voice assistant.
      Are you sure you want to proceed?
    </p>

    <div class="mt-6 flex justify-end gap-3">
      <button
        type="button"
        onclick={closeActivateModal}
        disabled={processing}
        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium  hover:bg-gray-50 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-700 dark: dark:hover:bg-gray-600"
      >
        Cancel
      </button>
      <button
        type="button"
        onclick={activateAssistant}
        disabled={processing}
        class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
      >
        {processing ? 'Activating...' : 'Activate'}
      </button>
    </div>
  </div>
</Modal>
