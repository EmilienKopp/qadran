<script lang="ts">
  import { preventDefault } from 'svelte/legacy';

  import Button from '$components/Actions/Button.svelte';
  import Input from '$components/DataInput/Input.svelte';
  import PasswordInput from '$components/DataInput/PasswordInput.svelte';
  import GuestLayout from '$layouts/GuestLayout.svelte';
  import { Link, useForm } from '@inertiajs/svelte';

  const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
  });

  const submit = () => {
    $form.post(route('register'), {
      onFinish: () => {
        console.log($form);
        $form.reset('password', 'password_confirmation');
      },
    });
  };
</script>

<GuestLayout>
  <form onsubmit={preventDefault(submit)}>
    <div class="flex flex-row gap-4">
      <Input
        label="First Name"
        id="first_name"
        name="first_name"
        type="text"
        class=""
        bind:value={$form.first_name}
        required
        autofocus
        autocomplete="name"
        error={$form.errors.first_name}
      />
      <Input
        label="Last Name"
        id="last_name"
        name="last_name"
        type="text"
        class=""
        bind:value={$form.last_name}
        required
        autofocus
        autocomplete="name"
        error={$form.errors.last_name}
      />
    </div>
    

    <div class="mt-4">
      <Input
        label="Email"
        id="email"
        name="email"
        type="email"
        class=""
        bind:value={$form.email}
        required
        autocomplete="username"
        error={$form.errors.email}
      />
    </div>

    <div class="mt-4">
      <PasswordInput
        label="Password"
        id="password"
        name="password"
        class=""
        bind:value={$form.password}
        required
        autocomplete="new-password"
        error={$form.errors.password}
      />
    </div>

    <div class="mt-4">
      <PasswordInput
        label="Confirm Password"
        id="password_confirmation"
        name="password_confirmation"
        class=""
        bind:value={$form.password_confirmation}
        required
        autocomplete="new-password"
        error={$form.errors.password_confirmation}
      />
    </div>

    <div class="mt-4 flex items-center justify-end">
      <Link
        href={route('login')}
        class="rounded-md text-sm  underline focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
      >
        Already registered?
      </Link>

      <Button
        class="ms-4 {$form.processing ? 'opacity-25' : ''}"
        disabled={$form.processing}
      >
        Register
      </Button>
    </div>
  </form>
</GuestLayout>
