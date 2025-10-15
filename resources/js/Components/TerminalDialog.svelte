<script lang="ts">
  import { onMount } from "svelte";
  import Terminal from "./Terminal.svelte";

  
  let dialog = $state<HTMLDialogElement | null>(null);
  let terminal: Terminal | null = null;

  function openModal() {
    if (dialog) {
      dialog.showModal();
      terminal?.focusInput();
    }
  }

  onMount(() => {
    // Open on Cmd+K OR Ctrl+K
    const handleKeydown = (event: KeyboardEvent) => {
      if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === "k") {
        event.preventDefault();
        console.log("Opening terminal modal...");
        openModal();
      }
    };

    window.addEventListener("keydown", handleKeydown);

    return () => {
      window.removeEventListener("keydown", handleKeydown);
    };
  })
</script>




<button onclick={openModal}>
  <kbd class="kbd">⌘</kbd>
  <kbd class="kbd">K</kbd>
</button>

<dialog bind:this={dialog} class="modal">
  <div class="modal-box w-11/12 max-w-5xl">
    <h3 class="text-lg font-bold">CLI</h3>
    <p class="py-4">Press ESC key or click the button below to close</p>
    <Terminal bind:this={terminal} />
    <div class="modal-action">
      <form method="dialog">
        <!-- if there is a button in form, it will close the modal -->
        <button class="btn">Close</button>
      </form>
    </div>
  </div>
</dialog>