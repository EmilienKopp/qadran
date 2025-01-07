<script lang="ts">
  export let checked: boolean = false;
</script>

<input
  type="checkbox"
  bind:checked
  class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
  {...$$restProps}
  on:click
  on:change
  on:keydown
  on:keyup
  on:keypress
  on:focus
/>
