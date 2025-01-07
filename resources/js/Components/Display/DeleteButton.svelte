<script lang="ts">
  import { Trash2 } from 'lucide-svelte';
  import { twMerge } from 'tailwind-merge';

  export let label: string;
</script>

<button
  class={twMerge('btn btn-error btn-sm btn-outline', $$restProps.class)}
  on:click
  title="Delete"
  type="button"
>
  <Trash2 class="w-4 h-4" />
  {label}
</button>
