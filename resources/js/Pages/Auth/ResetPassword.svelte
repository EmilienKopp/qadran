<script lang="ts">
  import GuestLayout from '$layouts/GuestLayout.svelte';
  import Button from '$components/Actions/Button.svelte';
  import Input from '$components/DataInput/Input.svelte';
  import { router, useForm } from '@inertiajs/svelte';

  export let email: string;
  export let token: string;

  const form = useForm({
    token,
    email,
    password: '',
    password_confirmation: '',
  });

  const submit = () => {
    form.post(route('password.store'), {
      onFinish: () => {
        form.reset('password', 'password_confirmation');
      },
    });
  };
</script>

<GuestLayout>
  <div slot="head">
    <title>Reset Password</title>
  </div>

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

    <div class="mt-4">
      <Input
        label="Password"
        id="password"
        type="password"
        class="mt-1 block w-full"
        bind:value={form.password}
        required
        autocomplete="new-password"
        errors={form.errors?.password}
      />
    </div>

    <div class="mt-4">
      <Input
        label="Confirm Password"
        id="password_confirmation"
        type="password"
        class="mt-1 block w-full"
        bind:value={form.password_confirmation}
        required
        autocomplete="new-password"
        errors={form.errors?.password_confirmation}
      />
    </div>

    <div class="mt-4 flex items-center justify-end">
      <Button
        class="ml-4"
        type="submit"
      >
        Reset Password
      </Button>
    </div>
  </form>
</GuestLayout>
