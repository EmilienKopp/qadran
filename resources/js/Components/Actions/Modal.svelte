<script lang="ts">
  import { on } from 'svelte/events';

  interface Props {
    id?: string;
    title?: import('svelte').Snippet;
    children?: import('svelte').Snippet;
    onclose?: (e?: Event) => void;
  }

  let dialog: HTMLDialogElement | undefined = $state();

  let { id, title, children }: Props = $props();

  export function close() {
    onclose?.();
    dialog?.close();
  }

  export function showModal() {
    dialog?.showModal();
  }
</script>

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
    <button onclick={close}>X</button>
  </form>
</dialog>
