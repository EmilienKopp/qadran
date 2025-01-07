<script lang="ts">
  import { twMerge } from "tailwind-merge";

  const { class: css, ...attributes } = $$restProps;
  
</script>
<button  {...attributes} class={twMerge("btn", css)}>
  <slot />
</button>