<script lang="ts">
  import { twMerge } from 'tailwind-merge';
  import clsx from 'clsx';
  import InputLabel from './InputLabel.svelte';
  import InputError from './InputError.svelte';

  interface Props {
    label?: string;
    name?: string;
    id?: string;
    required?: boolean;
    value?: string | number | File | null | Date;
    error?: string | null;
    errors?: string | string[] | null;
    class?: string;
    fieldsetClass?: string;
    hint?: string;
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
    id,
    required = false,
    value = $bindable(),
    error,
    errors,
    type = 'text',
    class: className = '',
    fieldsetClass = '',
    placeholder = 'Type here',
    hint = '',
    onchange,
    oninput,
    ...rest
  }: Props = $props();

  if (!name && id) {
    name = id;
  }

  if (name && !id) {
    id = name;
  }

  // Normalize error handling - support both 'error' and 'errors' props
  const normalizedError = $derived(
    error ||
      (typeof errors === 'string'
        ? errors
        : Array.isArray(errors)
          ? errors[0]
          : null)
  );

  let classes = $derived(
    clsx(
      'input input-bordered w-full',
      normalizedError && 'input-error',
      className
    )
  );

  const fieldsetClasses = $derived(twMerge('fieldset w-full', fieldsetClass));
</script>

<fieldset
  class={fieldsetClasses}
  data-error={normalizedError ? 'true' : 'false'}
>
  {#if label}
    <legend class="fieldset-legend">
      {label}
      {#if required}
        <span class="text-error">*</span>
      {/if}
    </legend>
  {/if}
  <input
    class={classes}
    {name}
    {id}
    bind:value
    {onchange}
    {oninput}
    {placeholder}
    {...rest}
    {type}
  />
  {#if hint}
    <p class="optional">{hint}</p>
  {/if}
  <InputError message={normalizedError} />
</fieldset>
