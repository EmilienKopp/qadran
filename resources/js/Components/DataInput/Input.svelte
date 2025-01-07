<script lang="ts">
  import { createBubbler } from 'svelte/legacy';

  const bubble = createBubbler();
  import { twMerge } from 'tailwind-merge';
  interface Props {
    label?: string;
    name: string;
    required?: boolean;
    value?: string | number | File | null;
    errors?: Record<string, string>;
    type?: 'text' | 'number' | 'file' | 'search';
    [key: string]: any
  }

  let {
    label = '',
    name,
    required = false,
    value = $bindable(''),
    errors = {},
    type = 'text',
    ...rest
  }: Props = $props();
</script>

<div class="form-control w-full mb-4">
  {#if label}
    <label class="label" for={rest.id}>
      <span class="label-text flex items-center">
        {label}
        {#if required}
          <span class="text-error ml-1">*</span>
        {/if}
      </span>
    </label>
  {/if}
  <input
    class={twMerge('input input-bordered', rest.class)}
    bind:value
    onchange={bubble('change')}
    oninput={bubble('input')}
    {...rest}
    {type}
  />
  {#if errors && errors[name]}
    <p class="text-error text-xs mt-1">{errors[name]}</p>
  {/if}
</div>
