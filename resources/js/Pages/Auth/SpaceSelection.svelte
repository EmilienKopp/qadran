<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import Input from '$components/DataInput/Input.svelte';
  import Select from '$components/DataInput/Select.svelte';
  import GuestLayout from '$layouts/GuestLayout.svelte';
  import { getPage } from '$lib/inertia';
  import { asSelectOptions } from '$lib/utils/formatting';
  import { Form } from '@inertiajs/svelte';
  import dayjs from 'dayjs';

  interface Props {
    rememberedSpaces?: string[];
    error?: string;
  }

  let {
    rememberedSpaces = [],
    error = undefined,
  }: Props = $props();

  let inputNewSpace = $state(rememberedSpaces.length === 0);
  let spaceValue = $state(rememberedSpaces.length > 0 ? rememberedSpaces[0].split('|')[0] : '');

  const spaceOptions = $derived(
    rememberedSpaces
      .map((s: string) => {
        const split = s.split('|');
        return {
          space: split[0],
          last_accessed: split[1]
        };
      })
      .sort((a, b) => dayjs(b.last_accessed).diff(dayjs(a.last_accessed))),
  );
</script>

<svelte:head>
  <title>Select Space</title>
</svelte:head>

<GuestLayout>
  <Form class="w-full max-w-md mx-auto" method="POST" action={route('space-selection.store')}>
    <h1 class="mb-4 text-center text-3xl font-bold">Select Your Space</h1>
    <p class="mb-8 text-center text-sm text-gray-600 dark:text-gray-400">
      Enter your organization's space identifier to continue
    </p>

    <hr class="border-gray-300 dark:border-gray-600 my-4" />

    {#if error}
      <div
        class="mb-4 rounded-lg bg-red-100 p-3 text-sm text-red-700 dark:bg-red-900/30 dark:text-red-400"
      >
        {error}
      </div>
    {/if}

    <div>
      {#if rememberedSpaces.length > 0 && !inputNewSpace}
        <label
          for="space"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300"
        >
          Select your space
        </label>
        <Select
          id="space-select"
          class="mt-1 block w-full"
          bind:value={spaceValue}
          name="space"
          options={asSelectOptions(spaceOptions, 'space', 'space')}
        ></Select>
        <button
          type="button"
          class="mt-2 text-sm text-blue-600 hover:underline dark:text-blue-400"
          onclick={() => {
            inputNewSpace = true;
            spaceValue = '';
          }}
        >
          Enter a different space
        </button>
      {:else}
        <Input
          label="Space"
          id="space"
          type="text"
          name="space"
          placeholder="e.g. acme"
          class="mt-1 block w-full"
          bind:value={spaceValue}
          required
          autofocus
          autocomplete="organization"
          error={getPage().props.errors?.space}
        />
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
          Your organization's space identifier (host)
        </p>
        {#if rememberedSpaces.length > 0}
          <button
            type="button"
            class="mt-2 text-sm text-blue-600 hover:underline dark:text-blue-400"
            onclick={() => {
              inputNewSpace = false;
              spaceValue = spaceOptions[0]?.space ?? '';
            }}
          >
            Choose from recent spaces
          </button>
        {/if}
      {/if}
      {#if getPage().props.errors?.space}
        <p class="mt-2 text-sm text-red-600 dark:text-red-400">
          {getPage().props.errors.space}
        </p>
      {/if}
    </div>

    <div class="mt-6">
      <Button
        class="w-full"
        type="submit"
      >
        Continue
      </Button>
    </div>

    <p class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
      Don't have a space?
      <a
        href="mailto:support@qadran.com"
        class="font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400"
      >
        Contact support
      </a>
    </p>
  </Form>
</GuestLayout>
