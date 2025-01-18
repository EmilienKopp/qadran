<script lang="ts">
  interface Props {
    title?: string;
    children?: import('svelte').Snippet;
  }

  let { title = '', children }: Props = $props();
</script>

<h2 class="text-xl font-bold text-gray-800 text-center py-4">
  {title}
  {@render children?.()}
</h2>

