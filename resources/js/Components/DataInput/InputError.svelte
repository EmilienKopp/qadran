<script lang="ts">
  import clsx from "clsx";

  interface Props {
    message?: string | null;
    class?: string;
}

  let { message, class: className }: Props = $props();
</script>

{#if message}
    <p class={clsx("text-error text-xs mt-2",className)}>{message}</p>
{/if}
