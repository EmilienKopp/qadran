import type { DataAction, DataHeader, IDataStrategy } from '$types/common/dataDisplay';

import { BaseDataDisplayStrategy } from '$lib/core/strategies/dataDisplayStrategy';
import type { Organization } from '$models';
import { Trash2 } from 'lucide-svelte';
import { date } from '$lib/utils/formatting';
import { router } from '@inertiajs/svelte';

export class FreelancerOrganizationTableStrategy
  extends BaseDataDisplayStrategy<Organization>
  implements IDataStrategy<Organization>
{
  

  protected defaultHeaders(): DataHeader<Organization>[] {
    return [
      { label: 'Name', key: 'name' },
      { label: 'Description', key: 'description' },
      { label: 'Type', key: 'type' },
      { label: 'Updated At', key: 'updated_at', formatter: date },
    ];
  }

  protected defaultActions(): DataAction<Organization>[] {
    return [
      {
        label: 'Edit',
        href: (row: Organization) => route('organization.edit', row.id),
      },
      {
        label: 'Delete',
        css: () => 'text-red-500',
        icon: () => Trash2,
        callback: (row: Organization) =>
          router.delete(route('organization.destroy', row.id)),
      },
    ];
  }
}
