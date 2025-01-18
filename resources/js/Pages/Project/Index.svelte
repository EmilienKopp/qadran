<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import { DataTable } from '$components/Display/DataTable';
  import Header from '$components/UI/Header.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import { ProjectTableContext } from '$lib/domain/Project/tableContext';
  import { Link, page, useForm } from '@inertiajs/svelte';

  interface Props {
    projects: any[];
    children?: import('svelte').Snippet;
  }
  const user = $page.props.auth.user;
  const context = new ProjectTableContext(user.roles?.[0].name);

  const headers = context.getHeaders();
  const actions = context.getActions();

  let { projects }: Props = $props();

</script>

<AuthenticatedLayout>
  <div class="p-8">
    <Header title="Projects">
      <Button href={route('project.create')}>Create Project</Button>
    </Header>


    <DataTable
      data={projects}
      {headers}
      {actions}
    />
  </div>
</AuthenticatedLayout>
