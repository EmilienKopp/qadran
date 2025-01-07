<script lang="ts">
  export let href: string;
  export let filename: string;
</script>

<a {href} download={filename} class="btn btn-sm btn-primary">
  <slot/>
</a>