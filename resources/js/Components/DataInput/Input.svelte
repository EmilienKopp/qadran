<script lang="ts">
  import { twMerge } from 'tailwind-merge';
  import clsx from 'clsx';
  import InputLabel from './InputLabel.svelte';
  interface Props {
    label?: string;
    name: string;
    required?: boolean;
    value?: string | number | File | null | Date;
    error?: string | null;
    class?: string;
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
    type = 'text',
    class: className = '',
    onchange,
    oninput,
    ...rest
  }: Props = $props();


  let classes = $derived(clsx(
    'input',
    error && 'input-error',
    className,
  ))
</script>

<fieldset class="fieldset" data-error={error ? 'true' : 'false'}>
  {#if label}
    <InputLabel for={rest.id} {required}>{label}</InputLabel>
  {/if}
  <input
    class={classes}
    name={name}
    bind:value
    {onchange}
    {oninput}
    {...rest}
    {type}
    placeholder="Type here"
  />
  {#if error}
    <p class="label text-error text-xs mt-1">{error}</p>
  {/if}
</fieldset>
