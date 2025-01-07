
<script lang="ts">
    
  interface Props {
    id: string;
    dialog: HTMLDialogElement;
    show?: boolean;
    title?: import('svelte').Snippet;
    children?: import('svelte').Snippet;
  }

  let {
    id,
    dialog = $bindable(),
    show = false,
    title,
    children
  }: Props = $props();

</script>

{#if show}
  <dialog class="modal" {id} bind:this={dialog}>
    <div class="modal-box">
      <h3 class="text-lg font-bold">
          {@render title?.()}
      </h3>
      <p class="py-4">
          {@render children?.()}
      </p>
    </div>

    <!-- This never displays -->
    <form method="dialog" class="modal-backdrop">
      <button>X</button>
    </form>
  </dialog>
{/if}