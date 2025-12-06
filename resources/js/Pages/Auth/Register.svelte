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
    space: '',
    host: '',
  });

  let hostChanged = false;

  const handleHostChange = (e: Event) => {
    hostChanged = true;
  };

  const handleOrgDisplayNameChange = (e: Event) => {
    if(hostChanged) {
      return;
    }
    const input = e.target as HTMLInputElement;
    console.log('handleOrgDisplayNameChange', input.value, hostChanged);
    $form.host = input.value.toLowerCase().replace(/[^a-z0-9-]/g, '').replace(/\s+/g, '-');
  };

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
      <Input
        label="Space/Organization Display Name"
        name="space"
        type="text"
        class=""
        placeholder="e.g. Acme Corporation"
        hint="This will be your organization's display name."
        bind:value={$form.space}
        oninput={handleOrgDisplayNameChange}
        required
        autocomplete="organization"
        error={$form.errors.space}
      />
    </div>

    
    <div class="mt-4">
      <Input
        label="Space/Organization Unique Name"
        name="host"
        type="text"
        class=""
        placeholder="e.g. acme"
        hint="This will be your organization's unique identifier used in URLs. It must be lowercase and contain no spaces."
        bind:value={$form.host}
        oninput={handleHostChange}
        required
        autocomplete="organization"
        error={$form.errors.host}
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
