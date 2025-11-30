<script lang="ts">
  import { preventDefault } from 'svelte/legacy';

  import GuestLayout from '$layouts/GuestLayout.svelte';
  import Button from '$components/Actions/Button.svelte';
  import { Link, useForm } from '@inertiajs/svelte';
  import { writable } from 'svelte/store';

  interface Props {
    status: string | undefined;
  }

  let { status }: Props = $props();

  const form = useForm({});

  const submit = () => {
    form.post(route('verification.send'));
  };

  const verificationLinkSent = writable(status === 'verification-link-sent');
</script>

<GuestLayout>
  {#snippet head()}
    <div >
      <title>Email Verification</title>
    </div>
  {/snippet}

  <div class="mb-4 text-sm ">
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

  <form onsubmit={preventDefault(submit)}>
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
        class="rounded-md text-sm  underline focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
      >
        Log Out
      </Link>
    </div>
  </form>
</GuestLayout>
