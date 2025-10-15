<script lang="ts">
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import Terminal from '$components/Terminal.svelte';

  interface Props {
    output: string[];
  }

  let { output = [] }: Props = $props();
  $inspect(output);
</script>

<AuthenticatedLayout>
  <Terminal {output} />
</AuthenticatedLayout>
