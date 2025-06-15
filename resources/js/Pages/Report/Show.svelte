<script lang="ts">
  import Button from "$components/Actions/Button.svelte";
  import DataList from "$components/Display/DataList.svelte";
  import FieldsetWrapper from "$components/UI/FieldsetWrapper.svelte";
  import Header from "$components/UI/Header.svelte";
  import AuthenticatedLayout from "$layouts/AuthenticatedLayout.svelte";
  import { date } from "$lib/utils/formatting";
  import type { Report } from "$models";

  interface Props {
    report: Report;
  }

  let { report }: Props = $props();

  const headers = [
    { key: "name", label: "Name" },
    { key: "description", label: "Description" },
    { key: "created_at", label: "Created At", formatter: date },
    { key: "updated_at", label: "Updated At", formatter: date },
  ];
</script>

<AuthenticatedLayout>
  <Header>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {report.name}
      
      <Button type="button" href={route('report.edit', report.id)} class="ml-4">
        Edit
      </Button>
    </h2>
  </Header>
  
  <FieldsetWrapper>
    <DataList {headers} data={report} />
  </FieldsetWrapper>
</AuthenticatedLayout>