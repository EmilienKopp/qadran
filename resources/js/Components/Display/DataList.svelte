<script lang="ts">
  import type { TableHeader } from '$types/common/table';

  interface Props {
    headers: TableHeader<any>[];
    data?: Record<string,any>;
  }

  let { headers, data }: Props = $props();
</script>

<dl>
  {#each headers as { key, label, formatter }}
    <dt>{label}:</dt>
    {#if data?.[key] === true}
      <dd>Yes</dd>
    {:else if data?.[key] === false}
      <dd>No</dd>
    {:else if data?.[key] !== null && data?.[key] !== undefined}
      <dd>{formatter ? formatter(data[key]) : data[key]}</dd>
    {:else}
      <dd> - </dd>
    {/if}
  {/each}
</dl>

<style>
  dl {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
  }

  dt {
    font-weight: bold;
  }
</style>