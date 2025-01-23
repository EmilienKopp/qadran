<script lang="ts">
  import { RateDatalistContext } from "$lib/domain/Rate/context";
  import DataList from "$components/Display/DataList.svelte";
  import { getUserRoleName } from "$lib/inertia";
  import AuthenticatedLayout from "$layouts/AuthenticatedLayout.svelte";
  import Header from "$components/UI/Header.svelte";
  import Button from "$components/Actions/Button.svelte";

  let { rate } = $props();
  let context = new RateDatalistContext(getUserRoleName());
  let headers = context.strategy.headers();
</script>

<AuthenticatedLayout>
  <Header>
    <h1 class="text-2xl font-bold">Rate</h1>
    <Button href={route('rate.index')}>Back</Button>
  </Header>
  <DataList {headers} data={rate} />
</AuthenticatedLayout>