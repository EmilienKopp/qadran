<script lang="ts">
  import { Link, inertia } from '@inertiajs/svelte';
  
  export let actions: DropdownAction[] = [];
</script>

<details class="dropdown dropdown-bottom">
  <summary class="flex rounded-md items-center cursor-pointer select-none" on:click={() => console.log("Dropdown")}>
    <slot name="trigger"></slot>
  </summary>

  <ul
    class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow transition delay-75"
  >
    {#each actions as {href, onclick, text, as}}
      <li>
        {#if as === 'a' || !as}
          <Link {href}>{text}</Link>
        {:else if as === 'button'}
          <button on:click={onclick} use:inertia="{{ href: href, method: 'post' }}" type="button">
            {text}
          </button>
        {:else}
          {text}
        {/if}
      </li>
    {/each}
  </ul>
</details>
