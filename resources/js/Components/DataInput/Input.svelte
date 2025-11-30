<script lang="ts">
  import { twMerge } from 'tailwind-merge';
  import clsx from 'clsx';
  import InputLabel from './InputLabel.svelte';
  import InputError from './InputError.svelte';
  
  interface Props {
    label?: string;
    name?: string;
    required?: boolean;
    value?: string | number | File | null | Date;
    error?: string | null;
    errors?: string | string[] | null;
    class?: string;
    fieldsetClass?: string;
    type?:
      | 'text'
      | 'number'
      | 'file'
      | 'search'
      | 'tel'
      | 'url'
      | 'email'
      | 'password'
      | 'date'
      | 'time'
      | 'datetime-local'
      | 'month'
      | 'week'
      | 'color';
    placeholder?: string;
    oninput?: (e: Event) => void;
    onchange?: (e: Event) => void;
    [key: string]: any;
  }

  let {
    label = '',
    name,
    required = false,
    value = $bindable(),
    error,
    errors,
    type = 'text',
    class: className = '',
    fieldsetClass = '',
    placeholder = 'Type here',
    onchange,
    oninput,
    ...rest
  }: Props = $props();

  if(!name && rest.id) {
    name = rest.id;
  }

  // Normalize error handling - support both 'error' and 'errors' props
  const normalizedError = $derived(
    error || 
    (typeof errors === 'string' ? errors : Array.isArray(errors) ? errors[0] : null)
  );

  let classes = $derived(clsx(
    'input input-bordered w-full',
    normalizedError && 'input-error',
    className,
  ))

  const fieldsetClasses = $derived(twMerge(
    'fieldset w-full',
    fieldsetClass
  ));
</script>

<fieldset class={fieldsetClasses} data-error={normalizedError ? 'true' : 'false'}>
  {#if label}
    <InputLabel for={rest.id} {required}>{label}</InputLabel>
  {/if}
  <input
    class={classes}
    {name}
    bind:value
    {onchange}
    {oninput}
    {placeholder}
    {...rest}
    {type}
  />
  <InputError message={normalizedError} />
</fieldset>
