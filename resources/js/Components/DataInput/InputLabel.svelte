
<script lang="ts">
  let { children, for: htmlFor, ...others } = $props();
</script>
<label class="block text-sm font-medium text-gray-700" for={htmlFor} {...others}> 
    {@render children?.()}
</label>