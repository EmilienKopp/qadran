<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import Modal from '$components/Actions/Modal.svelte';
  import Input from '$components/DataInput/Input.svelte';
  import InputLabel from '$components/DataInput/InputLabel.svelte';
  import InputError from '$components/DataInput/InputError.svelte';
  import { Form, useForm } from '@inertiajs/svelte';
  import VoiceAssistantController from '../../../actions/App/Http/Controllers/VoiceAssistantController';

  interface Props {
    preferences?: Record<string, any> | null;
  }

  let { preferences = null }: Props = $props();

  let preferencesModal: { showModal: () => void; close: () => void };
  let deactivateModal: { showModal: () => void; close: () => void };
  let formRef;

  const preferencesForm = useForm({
    preferences: {
      model: preferences?.model || 'claude-sonnet-4-20250514',
      timeout: preferences?.timeout || 30,
      max_tokens: preferences?.max_tokens || 4096,
      temperature: preferences?.temperature || 0.7,
    },
  });

  const deactivateForm = useForm({
    delete_workflow: false,
    revoke_tokens: false,
  });

  function openPreferencesModal() {
    preferencesModal?.showModal();
  }

  function closePreferencesModal() {
    preferencesModal?.close();
  }

  function openDeactivateModal() {
    $deactivateForm.reset();
    deactivateModal?.showModal();
  }

  function closeDeactivateModal() {
    deactivateModal?.close();
  }

  function deactivateAssistant(e: Event) {
    e.preventDefault();
    $deactivateForm.post(route('voice-assistant.deactivate'), {
      preserveScroll: true,
      onSuccess: () => {
        closeDeactivateModal();
      },
    });
  }
</script>

<section class="space-y-6">
  <header>
    <h2 class="text-lg font-medium  dark:">
      Voice Assistant Preferences
    </h2>
    <p class="mt-1 text-sm  dark:">
      Customize your AI voice assistant behavior and settings.
    </p>
  </header>

  <div class="space-y-4">
    <!-- Current Preferences -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
      <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
        <div class="text-sm font-medium  dark:">Model</div>
        <div class="mt-1 text-base font-semibold  dark:">
          {preferences?.model || 'claude-sonnet-4-20250514'}
        </div>
      </div>

      <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
        <div class="text-sm font-medium  dark:">Timeout</div>
        <div class="mt-1 text-base font-semibold  dark:">
          {preferences?.timeout || 30}s
        </div>
      </div>

      <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
        <div class="text-sm font-medium  dark:">Max Tokens</div>
        <div class="mt-1 text-base font-semibold  dark:">
          {preferences?.max_tokens?.toLocaleString() || '4,096'}
        </div>
      </div>

      <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
        <div class="text-sm font-medium  dark:">Temperature</div>
        <div class="mt-1 text-base font-semibold  dark:">
          {preferences?.temperature || 0.7}
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col gap-3 sm:flex-row">
      <Button onclick={openPreferencesModal}>
        Update Preferences
      </Button>
      <button
        type="button"
        onclick={openDeactivateModal}
        class="rounded-md border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50 dark:border-red-700 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-red-900/20"
      >
        Deactivate Assistant
      </button>
    </div>
  </div>
</section>

<!-- Update Preferences Modal -->
<Modal bind:this={preferencesModal}>
  <Form bind:this={formRef} action={VoiceAssistantController.updatePreferences()} method="POST" class="p-6" resetOnSuccess>
    <h3 class="text-lg font-medium  dark:">
      Update Preferences
    </h3>
    <p class="mt-2 text-sm  dark:">
      Customize how your AI voice assistant behaves.
    </p>

    <div class="mt-6 space-y-4">
      <div>
        <InputLabel for="model" value="AI Model" />
        <Input
          id="model"
          name="model"
          value="claude-sonnet-4-20250514"
          type="text"
          class="mt-1 block w-full"
          required
        />
      </div>

      <div>
        <InputLabel for="timeout" value="Timeout (seconds)" />
        <Input
          id="timeout"
          type="number"
          name="timeout"
          value="30"
          class="mt-1 block w-full"
          min="5"
          max="300"
          required
        />
      </div>

      <div>
        <InputLabel for="max_tokens" value="Max Tokens" />
        <Input
          id="max_tokens"
          type="number"
          name="max_tokens"
          value="4096"
          class="mt-1 block w-full"
          min="100"
          max="100000"
          required
        />
      </div>

      <div>
        <InputLabel for="temperature" value="Temperature" />
        <Input
          id="temperature"
          type="number"
          step="0.1"
          value="0.4"
          class="mt-1 block w-full"
          min="0"
          max="2"
          required
        />
        <p class="mt-1 text-xs  dark:">
          Higher values make output more random, lower values more focused
        </p>
      </div>
    </div>

    <div class="mt-6 flex justify-end gap-3">
      <button
        type="button"
        onclick={closePreferencesModal}
        disabled={formRef?.processing}
        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium  hover:bg-gray-50 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-700 dark: dark:hover:bg-gray-600"
      >
        Cancel
      </button>
      <button
        type="submit"
        disabled={formRef?.processing}
        class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
      >
        {formRef?.processing ? 'Saving...' : 'Save Preferences'}
      </button>
    </div>
  </Form>
</Modal>

<!-- Deactivate Confirmation Modal -->
<Modal bind:this={deactivateModal}>
  <form onsubmit={deactivateAssistant} class="p-6">
    <h3 class="text-lg font-medium  dark:">
      Deactivate AI Voice Assistant
    </h3>
    <p class="mt-2 text-sm  dark:">
      Are you sure you want to deactivate your AI voice assistant?
    </p>

    <div class="mt-6 space-y-4">
      <label class="flex items-start gap-3">
        <input
          type="checkbox"
          bind:checked={$deactivateForm.delete_workflow}
          class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
        />
        <div>
          <div class="text-sm font-medium  dark:">
            Delete workflow in n8n
          </div>
          <div class="text-xs  dark:">
            This will permanently remove the workflow from n8n
          </div>
        </div>
      </label>

      <label class="flex items-start gap-3">
        <input
          type="checkbox"
          bind:checked={$deactivateForm.revoke_tokens}
          class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
        />
        <div>
          <div class="text-sm font-medium  dark:">
            Revoke access tokens
          </div>
          <div class="text-xs  dark:">
            This will invalidate all AI Assistant tokens
          </div>
        </div>
      </label>
    </div>

    <div class="mt-6 flex justify-end gap-3">
      <button
        type="button"
        onclick={closeDeactivateModal}
        disabled={$deactivateForm.processing}
        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium  hover:bg-gray-50 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-700 dark: dark:hover:bg-gray-600"
      >
        Cancel
      </button>
      <button
        type="submit"
        disabled={$deactivateForm.processing}
        class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 disabled:opacity-50"
      >
        {$deactivateForm.processing ? 'Deactivating...' : 'Deactivate'}
      </button>
    </div>
  </form>
</Modal>
