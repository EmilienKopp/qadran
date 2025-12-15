<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import { DataTable } from '$components/Display/DataTable';
  import Header from '$components/UI/Header.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import { ReportContext } from '$lib/domain/Report/context';
  import { getAllUserRoles, getUserRoleName } from '$lib/inertia';
  import report from '../../routes/report';

  interface Props {
    reports: any[];
    children?: import('svelte').Snippet;
  }
  let role = $state(getUserRoleName());
  let roles = $state(getAllUserRoles());
  let context = $state(new ReportContext(role));
  let headers = $state(context.strategy.headers());
  let actions = $state(context.strategy.actions());

  let { reports }: Props = $props();

</script>

<AuthenticatedLayout>
  <div class="p-8">
    <Header title="Reports">
      <Button href={route('report.create')}>Create Report</Button>
    </Header>

    <DataTable
      data={reports}
      {headers}
      {actions}
    />
  </div>
</AuthenticatedLayout>