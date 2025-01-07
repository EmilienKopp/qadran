<script lang="ts">
  import Button from './Button.svelte';

  export let onCancel: fn = () => {};
  export let onSave: fn = () => {};
</script>

<div
  class="mt-6 flex flex-col gap-3 md:gap-2 md:flex-row md:items-center justify-center md:justify-end gap-x-6"
>
  <Button
    type="button"
    variant="outline"
    class=" hover:bg-gray-100 hover:text-primary"
    on:click={onCancel}>Cancel</Button
  >
  <Button type="submit" on:click={onSave}>Save</Button>
</div>
