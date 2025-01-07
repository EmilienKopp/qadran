<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import Modal from '$components/Modal.svelte';
  import SecondaryButton from '$components/SecondaryButton.svelte';
  import TextInput from '$components/TextInput.svelte';
  import { useForm } from '@inertiajs/svelte';
  import { tick } from 'svelte';

  let confirmingUserDeletion = false;
  let passwordInput: HTMLInputElement;

  const form = useForm({
      password: '',
  });

  const confirmUserDeletion = () => {
      confirmingUserDeletion = true;
      
      tick().then(() => passwordInput?.focus());
  };

  const deleteUser = () => {
      form.delete(route('profile.destroy'), {
          preserveScroll: true,
          onSuccess: () => closeModal(),
          onError: () => passwordInput?.focus(),
          onFinish: () => {
              $form.reset();
          },
      });
  };

  const closeModal = () => {
      confirmingUserDeletion = false;

      $form.clearErrors();
      $form.reset();
  };
</script>

<section class="space-y-6">
  <header>
      <h2 class="text-lg font-medium text-gray-900">
          Delete Account
      </h2>

      <p class="mt-1 text-sm text-gray-600">
          Once your account is deleted, all of its resources and data will
          be permanently deleted. Before deleting your account, please
          download any data or information that you wish to retain.
      </p>
  </header>

  <Button variant="danger" on:click={confirmUserDeletion}>Delete Account</Button>

  <Modal show={confirmingUserDeletion} on:close={closeModal}>
      <div class="p-6">
          <h2 class="text-lg font-medium text-gray-900">
              Are you sure you want to delete your account?
          </h2>

          <p class="mt-1 text-sm text-gray-600">
              Once your account is deleted, all of its resources and data
              will be permanently deleted. Please enter your password to
              confirm you would like to permanently delete your account.
          </p>

          <div class="mt-6">
              <InputLabel
                  for="password"
                  value="Password"
                  class="sr-only"
              />

              <TextInput
                  id="password"
                  bind:this={passwordInput}
                  bind:value={$form.password}
                  type="password"
                  class="mt-1 block w-3/4"
                  placeholder="Password"
                  on:keyup={(e) => e.key === 'Enter' && deleteUser()}
              />

              <InputError message={$form.errors.password} class="mt-2" />
          </div>

          <div class="mt-6 flex justify-end">
              <SecondaryButton on:click={closeModal}>
                  Cancel
              </SecondaryButton>

              <DangerButton
                  class="ms-3"
                  disabled={$form.processing}
                  on:click={deleteUser}
              >
                  Delete Account
              </DangerButton>
          </div>
      </div>
  </Modal>
</section>