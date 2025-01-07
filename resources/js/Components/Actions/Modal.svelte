
<script lang="ts">
    
  export let id: string;
  export let dialog: HTMLDialogElement;
  export let show: boolean = false;

</script>

{#if show}
  <dialog class="modal" {id} bind:this={dialog}>
    <div class="modal-box">
      <h3 class="text-lg font-bold">
          <slot name="title"/>
      </h3>
      <p class="py-4">
          <slot />
      </p>
    </div>

    <!-- This never displays -->
    <form method="dialog" class="modal-backdrop">
      <button>X</button>
    </form>
  </dialog>
{/if}