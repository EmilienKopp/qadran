<script lang="ts">
  import Button from "$components/Actions/Button.svelte";
  import DataList from "$components/Display/DataList.svelte";
  import FieldsetWrapper from "$components/UI/FieldsetWrapper.svelte";
  import Header from "$components/UI/Header.svelte";
  import AuthenticatedLayout from "$layouts/AuthenticatedLayout.svelte";
  import { superUseForm } from "$lib/inertia";
  import { date } from "$lib/utils/formatting";
  import type { Project } from "$models";

  interface Props {
    project: Project;
  }

  let { project }: Props = $props();

  const headers = [
    { key: "name", label: "Name" },
    { key: "description", label: "Description" },
    { key: "created_at", label: "Created At", formatter: date },
    { key: "start_date", label: "Start Date", formatter: date },
    { key: "end_date", label: "End Date", formatter: date },
    { key: "type", label: "Type" },
  ];
</script>


<AuthenticatedLayout>
  <Header>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {project.name}
      
      <Button type="button" href={route('project.edit', project.id)} class="ml-4">
        Edit
      </Button>
    </h2>
  </Header>
  
  <FieldsetWrapper>
    <DataList {headers} data={project} />
  </FieldsetWrapper>
</AuthenticatedLayout>