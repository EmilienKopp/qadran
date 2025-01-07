<script lang="ts">
  import GuestLayout from '$layouts/GuestLayout.svelte';
  import Button from '$components/Actions/Button.svelte';
  import Input from '$components/DataInput/Input.svelte';
  import { useForm } from '@inertiajs/svelte';

  const form = useForm({
    password: '',
  });

  const submit = () => {
    form.post(route('password.confirm'), {
      onFinish: () => {
        form.reset();
      },
    });
  };
</script>

<GuestLayout>
  <div slot="head">
    <title>Confirm Password</title>
  </div>

  <div class="mb-4 text-sm text-gray-600">
    This is a secure area of the application. Please confirm your
    password before continuing.
  </div>

  <form on:submit|preventDefault={submit}>
    <div>
      <Input
        label="Password"
        id="password"
        type="password"
        class="mt-1 block w-full"
        bind:value={form.password}
        required
        autocomplete="current-password"
        autofocus
        errors={form.errors?.password}
      />
    </div>

    <div class="mt-4 flex justify-end">
      <Button
        class="ms-4 {$form.processing ? 'opacity-25' : ''}"
        disabled={$form.processing}
      >
        Confirm
      </Button>
    </div>
  </form>
</GuestLayout>
