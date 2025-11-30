<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import Input from '$components/DataInput/Input.svelte';
  import InputError from '$components/DataInput/InputError.svelte';
  import SectionCard from '$components/UI/SectionCard.svelte';
  import { getPage } from '$lib/inertia';
  import { Form, Link } from '@inertiajs/svelte';

  interface Props {
    mustVerifyEmail?: boolean;
    status?: string;
    class?: string;
  }

  let { mustVerifyEmail = false, status, class: className }: Props = $props();

  const {
    props: {
      auth: { user },
    },
  } = getPage();
</script>

<SectionCard
  title="Profile Information"
  subtitle="Update your account's profile information and email address."
  class={className}
>
  {#snippet content()}
    <Form class="space-y-6" method="PATCH" action={route('profile.update')}>
      {#snippet children({ errors, processing }: any)}
        <fieldset>
          <div class="space-y-4 grid grid-cols-3 gap-4">
            <Input
              id="first_name"
              name="first_name"
              type="text"
              label="First Name"
              class="mt-1 block w-full"
              value={user.first_name}
              required
              autofocus
              autocomplete="name"
            />

            <Input
              id="middle_name"
              name="middle_name"
              type="text"
              label="Middle Name"
              class="mt-1 block w-full"
              value={user.middle_name}
              autocomplete="name"
            />

            <Input
              id="last_name"
              name="last_name"
              type="text"
              label="Last Name"
              class="mt-1 block w-full"
              value={user.last_name}
              required
              autocomplete="name"
            />

            <Input
              id="email"
              name="email"
              type="email"
              label="Email"
              class="mt-1 block w-full"
              value={user.email}
              fieldsetClass="col-span-full"
              required
              autocomplete="username"
            />
          </div>
          <InputError message={errors.first_name} />
          <InputError message={errors.middle_name} />
          <InputError message={errors.last_name} />
          <InputError message={errors.email} />
        </fieldset>

        {#if mustVerifyEmail && user.email_verified_at === null}
          <p class="mt-2 text-sm">
            Your email address is unverified.
            <Link
              href={route('verification.send')}
              method="post"
              as="button"
              class="rounded-md text-sm  underline focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
              Click here to re-send the verification email.
            </Link>
          </p>

          {#if status === 'verification-link-sent'}
            <div class="mt-2 text-sm font-medium text-green-600">
              A new verification link has been sent to your email address.
            </div>
          {/if}
        {/if}

        <div class="flex items-center gap-4">
          <Button variant="primary" disabled={processing}>Save</Button>
        </div>
      {/snippet}
    </Form>
  {/snippet}
</SectionCard>
