<script lang="ts">
  interface Props {
    message: string;
}

  let { message }: Props = $props();
</script>

{#if message}
    <p class="text-error text-xs mt-2">{message}</p>
{/if}
