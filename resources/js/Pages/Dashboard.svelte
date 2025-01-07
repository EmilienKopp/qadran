<script lang="ts">
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';

  let modal: HTMLDialogElement;
</script>

<svelte:head>
  <title>Dashboard</title>
</svelte:head>

<AuthenticatedLayout>
  {#snippet header()}
    <h2  class="text-xl font-semibold leading-tight text-gray-800">
      Dashboard
    </h2>
  {/snippet}

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 flex items-center">
          You're logged in! 🎉
        </div>
      </div>
    </div>
  </div>
</AuthenticatedLayout>
