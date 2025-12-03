<script lang="ts">
  import { Link } from '@inertiajs/svelte';

  interface Props {
    href: string;
    active?: boolean;
    children?: import('svelte').Snippet;
  }

  let { href, active = false, children }: Props = $props();

  // Compute the class dynamically
  let navClasses = $derived(active
    ? 'inline-flex items-center px-1 pt-1 border-b-2 border-primary text-sm font-medium leading-5  focus:outline-hidden focus:border-primary transition duration-150 ease-in-out'
    : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5  hover:border-gray-300 focus:outline-hidden focus: focus:border-gray-300 transition duration-150 ease-in-out');
</script>

<Link {href} class={navClasses} viewTransition>
  {@render children?.()}
</Link>
