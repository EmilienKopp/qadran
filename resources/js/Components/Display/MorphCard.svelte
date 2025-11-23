<script lang="ts">
  import type { Snippet } from 'svelte';
  import { getContext } from 'svelte';

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

  let openedFeatures = getContext<{ id: string | null }>('opened-features');
  let isExpanded = $derived(openedFeatures.id === id);

  function toggle() {
    const shouldOpen = !isExpanded;
    
    if (document.startViewTransition) {
      document.startViewTransition(() => {
        openedFeatures.id = shouldOpen ? id : null;
      });
    } else {
      openedFeatures.id = shouldOpen ? id : null;
    }
    console.log(openedFeatures);
  }

  const clickOutside = (node: HTMLElement) => {
    const handleClick = (event: MouseEvent) => {
      if (isExpanded && !node.contains(event.target as Node)) {
        openedFeatures.id = null;
        console.log(openedFeatures);
      }
    };

    document.addEventListener('click', handleClick, true);

    return {
      destroy() {
        document.removeEventListener('click', handleClick, true);
      }
    };
  };
</script>

<div class="morph-card relative {className}" use:clickOutside>
  <button
    type="button"
    class="w-full text-left bg-base-200 rounded-lg shadow-lg overflow-hidden hover:shadow-xl hover:rotate-1 transition-all duration-300"
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
      onclick={(e) => {
        e.stopPropagation();
        toggle();
      }}
    >
      ✕
    </button>
    <div class="p-6">
      {@render expanded()}
    </div>
  </div>
</div>
