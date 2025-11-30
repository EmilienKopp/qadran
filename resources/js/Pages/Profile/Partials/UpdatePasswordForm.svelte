<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import InputError from '$components/DataInput/InputError.svelte';
  import PasswordInput from '$components/DataInput/PasswordInput.svelte';
  import SectionCard from '$components/UI/SectionCard.svelte';
  import { Form } from '@inertiajs/svelte';

  interface Props {
    class?: string;
  }

  let { class: className }: Props = $props();
</script>

<SectionCard
  title="Update Password"
  subtitle="Ensure your account is using a long, random password to stay secure."
  class={className}
>
  {#snippet content()}
    <Form
      method="PATCH"
      action={route('password.update')}
      class="space-y-6"
    >
      {#snippet children({ processing, errors }: any)}
        <div>
          <PasswordInput
            id="current_password"
            type="password"
            name="current_password"
            label="Current Password"
            class="mt-1 block w-full"
            autocomplete="current-password"
          />

          <InputError message={errors.current_password} />
        </div>

        <div>
          <PasswordInput
            id="password"
            name="password"
            type="password"
            label="New Password"
            class="mt-1 block w-full"
            autocomplete="new-password"
          />

          <InputError message={errors.password} />
        </div>

        <div>
          <PasswordInput
            id="password_confirmation"
            type="password"
            name="password_confirmation"
            label="Confirm Password"
            class="mt-1 block w-full"
            autocomplete="new-password"
          />

          <InputError message={errors.password_confirmation} />
        </div>

        <div class="flex items-center gap-4">
          <Button variant="primary" disabled={processing}>Save</Button>
        </div>
      {/snippet}
    </Form>
  {/snippet}
</SectionCard>
