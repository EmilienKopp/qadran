<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import Checkbox from '$components/DataInput/Checkbox.svelte';
  import Input from '$components/DataInput/Input.svelte';
  import GuestLayout from '$layouts/GuestLayout.svelte';
  import { props } from '$lib/stores';
  import { Link, useForm } from '@inertiajs/svelte';

  export let canResetPassword = false;
  export let status: string | undefined = undefined;

  const form = useForm({
    email: '',
    password: '',
    remember: false,
  });

  function submit() {
    $form.post('/login', {
      onFinish: () => {
        $form.reset('password');
      },
    });
  }

  async function facebookLogin() {

  }
</script>

<svelte:head>
  <title>Log in</title>
</svelte:head>

<GuestLayout>
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
        bind:value={$form.email}
        required
        autofocus
        autocomplete="username"
        errors={$form.errors?.email}
      />
    </div>

    <div class="mt-4">
      <Input
        label="Password"
        id="password"
        type="password"
        class="mt-1 block w-full"
        bind:value={$form.password}
        required
        autocomplete="current-password"
        errors={$form.errors?.password}
      />
    </div>

    <div class="mt-4 block">
      <label class="flex items-center" for="remember">
        <Checkbox name="remember" bind:checked={$form.remember} />
        <span class="ms-2 text-sm text-gray-600">Remember me</span>
      </label>
    </div>

    <div class="mt-4 flex items-center justify-end">
      {#if canResetPassword}
        <Link href="/forgot-password"
          class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
          Forgot your password?
        </Link>
      {/if}

      <Button
        class="ms-4 {$form.processing ? 'opacity-25' : ''}"
        disabled={$form.processing}
      >
        Log in
      </Button>
    </div>
  </form>
  {#if $props.localEnv && location.href.includes("localhost")}
    <div>
      <ul>
        <li>candidate login: test@example.com</li>
        <li>employer login: employer@example.com</li>
        <li> <a href={route('facebook.redirect')}>Facebook Login</a></li>
      </ul> 
    </div>
  {/if}
</GuestLayout>
