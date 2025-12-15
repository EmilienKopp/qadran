<script lang="ts">
  import Button from '$components/Actions/Button.svelte';
  import DynamicFilterSearch from '$components/DataInput/DynamicFilterSearch.svelte';
  import { DataTable } from '$components/Display/DataTable';
  import Header from '$components/UI/Header.svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import { RateTableContext } from '$lib/domain/Rate/context';
  import { RoleContext } from '$lib/stores/global/roleContext.svelte';
  import { caseInsensitiveIncludes } from '$lib/utils/strings';
  import type { Rate } from '$models';
  import rate from '../../routes/rate';

  interface Props {
    rates: Rate[];
    q: string;
  }

  let { rates, q = $bindable('') }: Props = $props();
  let context = $derived(new RateTableContext(RoleContext.selected));
  let headers = $derived(context.strategy.headers());
  let actions = $derived(context.strategy.actions());
  let filteredRates = $state(rates);

  function search(query: string) {
    filteredRates = rates.filter((rate) => {
      return caseInsensitiveIncludes(rate.organization?.name, query)
        || caseInsensitiveIncludes(rate.user?.first_name, query)
        || caseInsensitiveIncludes(rate.user?.last_name, query)
        || caseInsensitiveIncludes(rate.project?.name, query)
        || caseInsensitiveIncludes(rate.rate_type?.name, query)
        || rate.amount == Number(query);
    });
  }

  function clear() {
    filteredRates = rates;
  }
</script>

<AuthenticatedLayout>
  <div class="p-8">
    <Header title="Rates">
      <div class="w-full flex items-center gap-2 justify-end">
        <DynamicFilterSearch bind:q searchHandler={search} clearHandler={clear} />
        <Button href={route('rate.create')}>Create Rate</Button>
      </div>
    </Header>

    <DataTable
      searchStrings={[q]}
      data={filteredRates}
      {headers}
      {actions}
    />
  </div>
</AuthenticatedLayout> 