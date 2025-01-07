<script lang="ts">
  import { createBubbler } from 'svelte/legacy';

  const bubble = createBubbler();
  import { createEventDispatcher } from 'svelte';
  import { twMerge } from 'tailwind-merge';
  interface Props {
    label: Props['label'];
    options?: Option[];
    value: Props['value'];
    placeholder?: string;
    [key: string]: any
  }

  let {
    label,
    options = [],
    value = $bindable(),
    placeholder = 'Select something',
    ...rest
  }: Props = $props();

  interface Option {
    value: string | number;
    name: string;
  }

  interface Props {
    label: string;
    options: Option[];
    value: string | number;
  }

</script>

<div class="form-control w-full mb-4">
  {#if label}
    <label class="label" for={rest.id}>
      <span class="label-text">{label}</span>
    </label>
  {/if}
  <select
    class={twMerge('select select-bordered', rest.class)}
    {...rest}
    bind:value
    onchange={bubble('change')}
  >
    {#if placeholder && !value}
      <option value="" disabled selected>{placeholder}</option>
    {/if}
    {#each options as option}
      <option value={option.value}>{option.name}</option>
    {/each}
  </select>
</div>
