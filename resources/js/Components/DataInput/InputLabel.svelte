<!-- @migration-task Error while migrating Svelte code: Unexpected keyword 'for'
https://svelte.dev/e/js_parse_error -->
<script lang="ts">
  let { for }: {for: string} = $props();
</script>
<label class="block text-sm font-medium text-gray-700"> 
    <slot/>
</label>