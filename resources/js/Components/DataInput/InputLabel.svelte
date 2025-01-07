
<script lang="ts">
  let { children, htmlFor } = $props();
</script>
<label class="block text-sm font-medium text-gray-700"> 
    {@render children?.()}
</label>