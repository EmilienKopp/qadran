<script lang="ts">
  import GuestLayout from '$layouts/GuestLayout.svelte';
  import Button from '$components/Actions/Button.svelte';
  import Input from '$components/DataInput/Input.svelte';
  import { router, useForm } from '@inertiajs/svelte';

  export let status: string | undefined;

  const form = useForm({
    email: '',
  });

  const submit = () => {
    form.post(route('password.email'));
  };
</script>

<GuestLayout>
  <div slot="head">
    <title>Forgot Password</title>
  </div>

  <div class="mb-4 text-sm text-gray-600">
    Forgot your password? No problem. Just let us know your email
    address and we will email you a password reset link that will allow
    you to choose a new one.
  </div>

  {#if status}
    <div class="mb-4 text-sm font-medium text-green-600">
      {status}
    </div>
  {/if}

  <form on:submit|preventDefault={submit}>
    <div>
      <Input
        label="Email"
        id="email"
        type="email"
        class="mt-1 block w-full"
        bind:value={form.email}
        required
        autofocus
        autocomplete="username"
        errors={form.errors?.email}
      />
    </div>

    <div class="mt-4 flex items-center justify-end">
      <Button
        class="ms-4 {$form.processing ? 'opacity-25' : ''}"
        disabled={$form.processing}
      >
        Email Password Reset Link
      </Button>
    </div>
  </form>
</GuestLayout>
