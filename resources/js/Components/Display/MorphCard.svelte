<script lang="ts">
  import type { Snippet } from 'svelte';

  interface Props {
    small: Snippet;
    expanded: Snippet;
    class?: string;
    id?: string;
  }

  let {
    small,
    expanded,
    class: className = '',
    id = crypto.randomUUID(),
  }: Props = $props();

  let isExpanded = $state(false);

  function toggle() {
    // isExpanded = !isExpanded;
    // return;
    if (document.startViewTransition) {
      document.startViewTransition(() => {
        isExpanded = !isExpanded;
      });
    } else {
      isExpanded = !isExpanded;
    }
  }
</script>

<div class="morph-card relative {className}">
  <button
    type="button"
    class="w-full text-left bg-base-200 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300"
    onclick={toggle}
    class:hidden={isExpanded}
  >
    {@render small()}
  </button>
  <div
    class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 bg-base-100 rounded-lg shadow-2xl overflow-auto w-full max-w-2xl max-h-[80vh] transition-all duration-300 p-6"
    class:hidden={!isExpanded}
  >
    <button
      type="button"
      class="absolute top-4 right-4 btn btn-sm btn-circle btn-ghost"
      onclick={toggle}
    >
      ✕
    </button>
    <div class="p-6">
      {@render expanded()}
    </div>
  </div>
</div>
