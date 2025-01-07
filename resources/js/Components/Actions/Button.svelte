<script lang="ts">
  import clsx from "clsx";
  import { twMerge } from "tailwind-merge";

  export let variant = "primary";
</script>

<button  {...$$restProps} 
  on:click
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
    $$restProps.class)}>

  <slot />
</button>