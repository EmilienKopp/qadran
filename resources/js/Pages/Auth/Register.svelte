<script lang="ts">
  import { preventDefault } from 'svelte/legacy';

  import Button from '@/Components/Actions/FormActionButtons.svelte';
  import Input from '@/Components/DataInput/Input.svelte';
  import GuestLayout from '@/Layouts/GuestLayout.svelte';
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
        type="text"
        class=""
        bind:value={$form.first_name}
        required
        autofocus
        autocomplete="first_name"
        errors={$form.errors.first_name}
      />
      <Input
        label="Last Name"
        id="last_name"
        type="text"
        class=""
        bind:value={$form.last_name}
        required
        autofocus
        autocomplete="last_name"
        errors={$form.errors.last_name}
      />
    </div>

    <div class="mt-4">
      <Input
        label="Email"
        id="email"
        type="email"
        class=""
        bind:value={$form.email}
        required
        autocomplete="username"
        errors={$form.errors.email}
      />
    </div>

    <div class="mt-4">
      <Input
        label="Password"
        id="password"
        type="password"
        class=""
        bind:value={$form.password}
        required
        autocomplete="new-password"
        errors={$form.errors.password}
      />
    </div>

    <div class="mt-4">
      <Input
        label="Confirm Password"
        id="password_confirmation"
        type="password"
        class=""
        bind:value={$form.password_confirmation}
        required
        autocomplete="new-password"
        errors={$form.errors.password_confirmation}
      />
    </div>

    <div class="mt-4 flex items-center justify-end">
      <Link
        href={route('login')}
        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
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
