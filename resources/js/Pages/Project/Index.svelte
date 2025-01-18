<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import { DataTable } from '$components/Display/DataTable';
  import Header from '$components/UI/Header.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import { router } from '@inertiajs/svelte';

  interface Props {
    projects: any[];
    children?: import('svelte').Snippet;
    auth: any;
  }

  $effect(() => {
    console.log(window);
  });

  let { projects, children, auth }: Props = $props();
</script>

<AuthenticatedLayout>
  <div class="p-8">
    <Header title="Projects">
      <Button href={route('project.create')}>Create Project</Button>
    </Header>


    <DataTable
      data={projects}
    />
  </div>
</AuthenticatedLayout>
