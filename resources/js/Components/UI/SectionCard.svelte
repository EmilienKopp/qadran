<script lang="ts">
  import clsx from 'clsx';
  import type { Snippet } from 'svelte';

  interface Props {
    title: string;
    subtitle?: string;
    class?: string;
    content: Snippet;
  }

  let { title, subtitle, class: className, content }: Props = $props();

  const classes = $derived(clsx('card card-border bg-base-100', className));
</script>

<section class={classes}>
  <div class="card-body flex flex-col justify-between h-full">
    <h2 class="card-title">{title}</h2>

    {#if subtitle}
      <p class="mt-1 text-sm max-h-fit">
        {subtitle}
      </p>
    {/if}

    <div class="flex-1 flex-col justify-between h-full [&>*]:h-full [&>*]:flex [&>*]:flex-col [&>*]:justify-between mt-4">
      {@render content()}
    </div>
  </div>
</section>
