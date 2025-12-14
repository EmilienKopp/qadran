<script lang="ts">
  import { router } from '@inertiajs/svelte';
  import AuthenticatedLayout from '$layouts/AuthenticatedLayout.svelte';
  import Header from '$components/UI/Header.svelte';
  import Input from '$components/DataInput/Input.svelte';
  import Select from '$components/DataInput/Select.svelte';
  import Button from '$components/Actions/Button.svelte';
  import DatePicker from '$components/DataInput/DatePicker.svelte';
  import FieldsetWrapper from '$components/UI/FieldsetWrapper.svelte';
  import type { SelectOption } from '$types/index';
  import type { Rate, RateType, Organization, Project } from '$models';
  import { superUseForm } from '$lib/inertia';
  import { asSelectOptions } from '$lib/utils/formatting';
  import { enums } from '$lib/inertia';

  interface Props {
    frequenciesOptions: SelectOption[];
    scopesOptions: SelectOption[];
    organizations: Organization[];
    projects: Project[];
  }

  let {
    frequenciesOptions,
    scopesOptions,
    organizations,
    projects,
  }: Props = $props();

  let organizationsOptions = asSelectOptions(organizations, 'id', 'name');
  let projectsOptions = asSelectOptions(projects, 'id', 'name');
  let rateTypesOptions = asSelectOptions(enums('rate_types') ?? [], 'value', 'name');
  let scope: 'organization' | 'project' | 'user' = $state('organization');

  let form = superUseForm<Rate>({
    rate_type: undefined,
    rate_frequency: undefined,
    amount: undefined,
    currency: 'USD',
    organization_id: undefined,
    project_id: undefined,
    user_id: undefined,
    overtime_multiplier: 1.5,
    holiday_multiplier: 2.0,
    special_multiplier: 2.5,
    custom_multiplier_rate: undefined,
    custom_multiplier_label: undefined,
    is_default: false,
    effective_from: undefined,
    effective_until: undefined,
  });

  function handleSubmit(e: SubmitEvent) {
    e.preventDefault();
    $form.post(route('rate.store'));
  }
</script>

<AuthenticatedLayout>
  <div class="p-8">
    <Header title="Create Rate" />

    <FieldsetWrapper>
      <form onsubmit={handleSubmit} class="space-y-6">
        <div class="flex flex-col gap-6">
          <!-- Scope Information -->
          <fieldset
            class="border rounded-md p-4 grid grid-cols-1 md:grid-cols-2 gap-6"
          >
            <legend class="text-lg font-medium">Scope Information</legend>
            <Select
              label="Scope"
              name="scope"
              options={scopesOptions}
              bind:value={scope}
              required
            />
            {#if scope === 'organization'}
              <Select
                label="Organization"
                name="organization_id"
                options={organizationsOptions}
                bind:value={$form.organization_id}
                error={$form.errors.organization_id}
                required
              />
            {:else if scope === 'project'}
              <Select
                label="Project"
                name="project_id"
                options={projectsOptions}
                bind:value={$form.project_id}
                error={$form.errors.project_id}
                required
              />
            {/if}
          </fieldset>

          <!-- Basic Rate Information -->
          <fieldset
            class="border rounded-md p-4 grid grid-cols-1 md:grid-cols-2 gap-6"
          >
            <legend class="text-lg font-medium">Basic Rate Information</legend>
            <Select
              label="Rate Type"
              name="rate_type"
              options={rateTypesOptions}
              bind:value={$form.rate_type}
              error={$form.errors.rate_type}
              required
            />

            <Select
              label="Frequency"
              name="rate_frequency"
              options={frequenciesOptions}
              bind:value={$form.rate_frequency}
              error={$form.errors.rate_frequency}
              required
            />

            <Input
              type="number"
              label="Amount"
              name="amount"
              bind:value={$form.amount}
              min="0"
              step="0.01"
              error={$form.errors.amount}
              required
            />

            <Input
              type="text"
              label="Currency"
              name="currency"
              bind:value={$form.currency}
              maxlength="3"
              placeholder="USD"
              error={$form.errors.currency}
              required
            />
          </fieldset>

          <!-- Multipliers -->
          <fieldset
            class="border rounded-md p-4 grid grid-cols-1 md:grid-cols-2 gap-6"
          >
            <legend class="text-lg font-medium">Multipliers (Optional)</legend>
            <Input
              type="number"
              label="Overtime Multiplier"
              name="overtime_multiplier"
              bind:value={$form.overtime_multiplier}
              step="0.1"
              min="1"
            />

            <Input
              type="number"
              label="Holiday Multiplier"
              name="holiday_multiplier"
              bind:value={$form.holiday_multiplier}
              step="0.1"
              min="1"
            />

            <Input
              type="number"
              label="Special Multiplier"
              name="special_multiplier"
              bind:value={$form.special_multiplier}
              step="0.1"
              min="1"
            />

            <Input
              type="number"
              label="Custom Multiplier Rate"
              name="custom_multiplier_rate"
              bind:value={$form.custom_multiplier_rate}
              step="0.1"
              min="1"
            />

            <Input
              type="text"
              label="Custom Multiplier Label"
              name="custom_multiplier_label"
              bind:value={$form.custom_multiplier_label}
              placeholder="e.g., Weekend Rate"
            />
          </fieldset>

          <!-- Effective Dates -->
          <fieldset
            class="border rounded-md p-4 grid grid-cols-1 md:grid-cols-2 gap-6"
          >
            <legend class="text-lg font-medium">Effective Dates (Optional)</legend>
            <DatePicker
              label="Effective From"
              name="effective_from"
              bind:value={$form.effective_from}
            />

            <DatePicker
              label="Effective Until"
              name="effective_until"
              bind:value={$form.effective_until}
            />
          </fieldset>
        </div>

        <div class="flex justify-end space-x-4">
          <Button type="button" variant="secondary" href={route('rate.index')}>
            Cancel
          </Button>
          <Button type="submit">Create Rate</Button>
        </div>
      </form>
    </FieldsetWrapper>
  </div>
</AuthenticatedLayout>
