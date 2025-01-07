<script lang="ts">
    import { twMerge } from 'tailwind-merge';
    export let title: string = '';
    export let description: string = '';
  </script>
  
  <fieldset {...$$restProps} class={twMerge("p-6 bg-base-100 border border-primary rounded-lg", $$restProps.class)}>
    <legend class="md:text-lg font-semibold text-primary">
      {title}
    </legend>
    {#if description}
      <p class="mt-1 text-xs sm:text-sm text-gray-600">{description}</p>
    {/if}
    <div class="mt-4">
      <slot />
    </div>
  </fieldset>
  