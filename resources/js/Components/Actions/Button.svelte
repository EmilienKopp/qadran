<script lang="ts">
  import { createBubbler } from 'svelte/legacy';

  const bubble = createBubbler();
  import clsx from "clsx";
  import { twMerge } from "tailwind-merge";

  interface Props {
    variant?: string;
    children?: import('svelte').Snippet;
    [key: string]: any
  }

  let { variant = "primary", children, ...rest }: Props = $props();
</script>

<button  {...rest} 
  onclick={bubble('click')}
  class={twMerge(
    "btn",
    clsx({
      "btn-error": variant === "danger",
      "btn-primary": variant === "primary",
      "btn-secondary": variant === "secondary",
      "btn-accent": variant === "accent",
      "btn-outline": variant === "outline",
      "btn-link": variant === "link",
    }),
    rest.class)}>

  {@render children?.()}
</button>