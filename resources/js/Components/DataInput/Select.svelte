<script lang="ts">
  import { createEventDispatcher } from 'svelte';
  import { twMerge } from 'tailwind-merge';
  export let label: Props['label'];
  export let options: Option[] = [];
  export let value: Props['value'];
  export let placeholder: string = 'Select something';

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
    <label class="label" for={$$restProps.id}>
      <span class="label-text">{label}</span>
    </label>
  {/if}
  <select
    class={twMerge('select select-bordered', $$restProps.class)}
    {...$$restProps}
    bind:value
    on:change
  >
    {#if placeholder && !value}
      <option value="" disabled selected>{placeholder}</option>
    {/if}
    {#each options as option}
      <option value={option.value}>{option.name}</option>
    {/each}
  </select>
</div>
