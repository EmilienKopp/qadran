<script lang="ts">
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import DeleteUserForm from './Partials/DeleteUserForm.svelte';
  import UpdatePasswordForm from './Partials/UpdatePasswordForm.svelte';
  import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.svelte';

  interface Props {
    mustVerifyEmail?: boolean;
    status?: string;
  }

  interface Props {
    mustVerifyEmail?: Props['mustVerifyEmail'];
    status?: Props['status'];
  }

  let { mustVerifyEmail = false, status = undefined }: Props = $props();
</script>
<svelte:head>
  <title>Profile</title>
</svelte:head>

<AuthenticatedLayout>
  {#snippet header()}
  
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Profile</h2>
    
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
        <DeleteUserForm class="max-w-xl" />
      </div>
    </div>
  </div>
</AuthenticatedLayout>
