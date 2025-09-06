<script lang="ts">
  import { twMerge } from 'tailwind-merge';
  import InputLabel from './InputLabel.svelte';
  interface Props {
    label?: string;
    options?: Option[];
    value: any;
    placeholder?: string;
    error?: string | null;
    onchange?: (e: Event) => void;
    [key: string]: any
  }

  let {
    label,
    options = [],
    value = $bindable(),
    placeholder = 'Select something',
    error,
    onchange,
    ...rest
  }: Props = $props();

  interface Option {
    value: any;
    name: string;
  }

</script>

<fieldset class="fieldset" data-error={error ? 'true' : 'false'}>
  {#if label}
    <InputLabel for={rest.id} required={rest.required}>
      {label}
    </InputLabel>
  {/if}
  <select
    class='select border rounded-md border-zinc-400'
    name={rest.name}
    {...rest}
    bind:value
    onchange={(e) => onchange?.(e)}
  >
    {#if placeholder && !value}
      <option value="" disabled selected>{placeholder}</option>
    {/if}
    {#each options as option}
      <option value={option.value}>{option.name}</option>
    {/each}
  </select>
</fieldset>
