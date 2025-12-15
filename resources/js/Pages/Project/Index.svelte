<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import { DataTable } from '$components/Display/DataTable';
  import Header from '$components/UI/Header.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import { ProjectContext } from '$lib/domain/Project/context';
  import { getAllUserRoles, getUserRoleName } from '$lib/inertia';
  import project from '../../routes/project';

  interface Props {
    projects: any[];
    children?: import('svelte').Snippet;
  }
  let role = $state(getUserRoleName());
  let roles = $state(getAllUserRoles());
  let context = $state(new ProjectContext(role));
  let headers = $state(context.strategy.headers());
  let actions = $state(context.strategy.actions());

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
