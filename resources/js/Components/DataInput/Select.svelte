<script lang="ts">
  import { twMerge } from 'tailwind-merge';
  import clsx from 'clsx';
  import InputLabel from './InputLabel.svelte';
  import InputError from './InputError.svelte';
  
  interface Props {
    label?: string;
    options?: Option[];
    value?: any;
    placeholder?: string;
    error?: string | null;
    errors?: string | string[] | null;
    required?: boolean;
    class?: string;
    items?: any[];
    mapping?: { valueColumn: string; labelColumn: string };
    onchange?: (e: Event) => void;
    [key: string]: any
  }

  let {
    label,
    options = [],
    items,
    mapping,
    value = $bindable(),
    placeholder = 'Select something',
    error,
    errors,
    required = false,
    class: className = '',
    onchange,
    ...rest
  }: Props = $props();

  interface Option {
    value: any;
    name: string;
  }

  // Normalize error handling - support both 'error' and 'errors' props
  const normalizedError = $derived(
    error || 
    (typeof errors === 'string' ? errors : Array.isArray(errors) ? errors[0] : null)
  );

  let classes = $derived(clsx(
    'select select-bordered w-full',
    normalizedError && 'select-error',
    className,
  ))

  if(!options?.length && items && mapping) {
    options = items.map(item => ({
      value: item[mapping.valueColumn],
      name: item[mapping.labelColumn],
    }));
  }
</script>

<fieldset class="fieldset w-full" data-error={normalizedError ? 'true' : 'false'}>
  {#if label}
    <InputLabel for={rest.id} {required}>
      {label}
    </InputLabel>
  {/if}
  <select
    class={classes}
    name={rest.name}
    {...rest}
    bind:value
    onchange={(e) => onchange?.(e)}
  >
    {#if placeholder && !value}
      <option value="" disabled selected>{placeholder}</option>
    {/if}
    {#each options as option}
      <option value={option.value} class="text-black">{option.name}</option>
    {/each}
  </select>
  <InputError message={normalizedError} />
</fieldset>
