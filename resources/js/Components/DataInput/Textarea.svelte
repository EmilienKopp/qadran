<script lang="ts">
  import { createBubbler } from 'svelte/legacy';

  const bubble = createBubbler();
  import { twMerge } from 'tailwind-merge';
  import InputLabel from './InputLabel.svelte';
  interface Props {
    label?: string;
    required?: boolean;
    value?: string;
    error?: string | null;
    placeholder?: string;
    name?: string;
    [key: string]: any;
  }

  let {
    label = '',
    required = false,
    value = $bindable(),
    class: className,
    placeholder = 'Enter text here',
    error,
    name = '',
    ...rest
  }: Props = $props();
</script>

<fieldset class="fieldset" data-error={error ? 'true' : 'false'}>

  {#if label}
    <InputLabel {required}>{label}</InputLabel>
  {/if}

  <textarea
    class={twMerge(
      "textarea h-24 border rounded-md border-zinc-400",
      className
    )}
    bind:value
    {name}
    onchange={bubble('change')}
    oninput={bubble('input')}
    {...rest}
    {placeholder}
  ></textarea>

  {#if error}
    <p class="label text-error text-xs mt-1">{error}</p>
  {/if}

</fieldset>
