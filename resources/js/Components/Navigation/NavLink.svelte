<script lang="ts">
  import { Link } from '@inertiajs/svelte';

  export let href: string;
  export let active: boolean = false;

  // Compute the class dynamically
  $: navClasses = active
    ? 'inline-flex items-center px-1 pt-1 border-b-2 border-primary text-md font-medium leading-5 text-gray-900 focus:outline-none focus:border-primary transition duration-150 ease-in-out'
    : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-md font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
</script>

<Link {href} class={navClasses}>
  <slot />
</Link>
