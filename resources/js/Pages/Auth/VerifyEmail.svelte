<script lang="ts">
  import GuestLayout from '$layouts/GuestLayout.svelte';
  import Button from '$components/Actions/Button.svelte';
  import { Link, useForm } from '@inertiajs/svelte';
  import { writable } from 'svelte/store';

  export let status: string | undefined;

  const form = useForm({});

  const submit = () => {
    form.post(route('verification.send'));
  };

  const verificationLinkSent = writable(status === 'verification-link-sent');
</script>

<GuestLayout>
  <div slot="head">
    <title>Email Verification</title>
  </div>

  <div class="mb-4 text-sm text-gray-600">
    Thanks for signing up! Before getting started, could you verify your
    email address by clicking on the link we just emailed to you? If you
    didn't receive the email, we will gladly send you another.
  </div>

  {#if $verificationLinkSent}
    <div class="mb-4 text-sm font-medium text-green-600">
      A new verification link has been sent to the email address you
      provided during registration.
    </div>
  {/if}

  <form on:submit|preventDefault={submit}>
    <div class="mt-4 flex items-center justify-between">
      <Button
        class="ml-4"
        type="submit"
      >
        Resend Verification Email
      </Button>

      <Link
        href={route('logout')}
        method="post"
        as="button"
        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
      >
        Log Out
      </Link>
    </div>
  </form>
</GuestLayout>
