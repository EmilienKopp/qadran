<script lang="ts">
  import { twMerge } from 'tailwind-merge';
  export let label: string = '';
  export let name: string;
  export let required: boolean = false;
  export let value: string | number | File | null = '';
  export let errors: Record<string, string> = {};
  export let type: 'text' | 'number' | 'file' | 'search' = 'text';
</script>

<div class="form-control w-full mb-4">
  {#if label}
    <label class="label" for={$$restProps.id}>
      <span class="label-text flex items-center">
        {label}
        {#if required}
          <span class="text-error ml-1">*</span>
        {/if}
      </span>
    </label>
  {/if}
  <input
    class={twMerge('input input-bordered', $$restProps.class)}
    bind:value
    on:change
    on:input
    {...$$restProps}
    {type}
  />
  {#if errors && errors[name]}
    <p class="text-error text-xs mt-1">{errors[name]}</p>
  {/if}
</div>
