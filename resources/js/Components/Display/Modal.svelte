<script lang="ts">
  export let isOpen: boolean = false;
  export let size: 'sm' | 'md' | 'lg' | 'xl' = 'md';

  const sizeClasses = {
    sm: 'max-w-sm',
    md: 'max-w-md',
    lg: 'max-w-lg',
    xl: 'max-w-xl'
  };

</script>

{#if isOpen}
  <div 
    class="modal modal-open"
    role="dialog"
    aria-modal="true"
  >
    <div class="modal-backdrop bg-black/50" />
    <div class="modal-box {sizeClasses[size]} relative">
      <slot />
    </div>
  </div>
{/if}

<style>
  .modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }
</style>