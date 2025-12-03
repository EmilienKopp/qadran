<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import Checkbox from '$components/DataInput/Checkbox.svelte';
  import Input from '$components/DataInput/Input.svelte';
  import Select from '$components/DataInput/Select.svelte';
  import GuestLayout from '$layouts/GuestLayout.svelte';
  import { getPage } from '$lib/inertia';
  import { asSelectOptions } from '$lib/utils/formatting';
  import { Link, useForm } from '@inertiajs/svelte';
  import dayjs from 'dayjs';

  interface Props {
    canResetPassword?: boolean;
    status?: string;
    rememberedSpaces?: string[];
  }

  let {
    canResetPassword = false,
    status = undefined,
    rememberedSpaces = [],
  }: Props = $props();
  let formElement: HTMLFormElement;
  let inputNewSpace = $state(rememberedSpaces.length === 0);
  const spaceOptions = $derived(
    rememberedSpaces
      .map((s: string) => {
        const split = s.split('|');
        return {
          space: split[0],
          last_accessed: split[1]
        };
      })
      .sort((a, b) => dayjs(a.last_accessed).diff(dayjs(b.last_accessed))),
  );

  const form = useForm({
    space: rememberedSpaces.length > 0 ? spaceOptions[0].space : '',
    email: '',
    password: '',
    remember: false,
  });

  function submit(e: SubmitEvent) {
    e.preventDefault();
    $form.post('/welcome/login', {
      onFinish: (event) => {
        $form.reset('password');
      },
    });
  }
</script>

<svelte:head>
  <title>Log in</title>
</svelte:head>

<GuestLayout>
  <form bind:this={formElement} class="w-full max-w-md mx-auto">
    <h1 class="mb-8 text-center text-3xl font-bold">Welcome Back</h1>
    <hr class="border-gray-300 dark:border-gray-600 my-4" />

    <div>
      {#if rememberedSpaces.length > 0}
        <label
          for="space"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300"
        >
          Select your space
        </label>
        <Select
          id="space-select"
          class="mt-1 block w-full"
          bind:value={$form.space}
          options={asSelectOptions(spaceOptions, 'space', 'space')}
        ></Select>
        <button
          type="button"
          class="mt-2 text-sm text-blue-600 hover:underline"
          onclick={() => (inputNewSpace = true)}
        >
          Enter a new space
        </button>
      {/if}
      {#if !rememberedSpaces.length || inputNewSpace}
        <Input
          label="Space"
          id="space"
          type="text"
          name="space"
          placeholder="e.g. acme"
          class="mt-1 block w-full"
          bind:value={$form.space}
          required
          autofocus
          autocomplete="organization"
          error={$form.errors?.space ?? getPage().props.errors?.space}
        />
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
          Your organization's space identifier
        </p>
      {/if}
    </div>

    <hr class="border-gray-300 dark:border-gray-600 my-4" />

    {#if status}
      <div
        class="mb-4 rounded-lg bg-green-100 p-3 text-sm text-green-700 dark:bg-green-900/30 dark:text-green-400"
      >
        {status}
      </div>
    {/if}

    <!-- OAuth Login Options -->
    <div class="space-y-3 mb-6">
      <!-- GitHub OAuth -->
      <a
        href={route('github.login', { space: $form.space })}
        class="flex items-center justify-center gap-3 rounded-lg bg-gray-900 px-6 py-3 font-semibold text-white transition-colors hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200 w-full"
      >
        <svg
          class="h-5 w-5"
          fill="currentColor"
          viewBox="0 0 24 24"
          aria-hidden="true"
        >
          <path
            fill-rule="evenodd"
            d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
            clip-rule="evenodd"
          />
        </svg>
        <span>Continue with GitHub</span>
      </a>

      <!-- Google OAuth -->
      <a
        href={route('google.login', { space: $form.space })}
        class="flex items-center justify-center gap-3 rounded-lg border-2 border-gray-300 px-6 py-3 font-semibold text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800 w-full"
      >
        <svg class="h-5 w-5" viewBox="0 0 24 24">
          <path
            fill="#4285F4"
            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
          />
          <path
            fill="#34A853"
            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
          />
          <path
            fill="#FBBC05"
            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
          />
          <path
            fill="#EA4335"
            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
          />
        </svg>
        <span>Continue with Google</span>
      </a>
    </div>

    <!-- Divider -->
    <div class="relative mt-6 mb-2">
      <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
      </div>
      <div class="relative flex justify-center text-sm">
        <span
          class="px-2 bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400"
          >Or continue with email</span
        >
      </div>
    </div>

    <!-- Email/Password Form -->
    <div>
      <div class="mt-2">
        <Input
          label="Email"
          id="email"
          type="email"
          name="email"
          class="mt-1 block w-full"
          bind:value={$form.email}
          required
          autocomplete="username"
          error={$form.errors?.email}
        />
      </div>

      <div class="mt-4">
        <Input
          label="Password"
          id="password"
          type="password"
          name="password"
          class="mt-1 block w-full"
          bind:value={$form.password}
          required
          autocomplete="current-password"
          error={$form.errors?.password}
        />
      </div>

      <div class="mt-4 block">
        <label class="flex items-center" for="remember">
          <Checkbox name="remember" bind:checked={$form.remember} />
          <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"
            >Remember me</span
          >
        </label>
      </div>

      <div class="mt-6 flex items-center justify-between">
        {#if canResetPassword}
          <Link
            href="/welcome/forgot-password"
            class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 underline"
          >
            Forgot password?
          </Link>
        {/if}

        <Button
          class="ms-auto {$form.processing ? 'opacity-25' : ''}"
          disabled={$form.processing}
        >
          {$form.processing ? 'Logging in...' : 'Log in'}
        </Button>
      </div>
    </div>

    <!-- Sign up link -->
    <p class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
      Don't have an account?
      <Link
        href="/welcome/register"
        class="font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400"
      >
        Sign up
      </Link>
    </p>
  </form>
</GuestLayout>
