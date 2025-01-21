import type { TableAction, TableHeader, TableStrategy } from '$types/common/table';

import { BaseTableStrategy } from '$lib/domain/common/tableStrategy';
import type { Organization } from '$models';
import { Trash2 } from 'lucide-svelte';
import { date } from '$lib/utils/formatting';

export class FreelancerOrganizationTableStrategy
  extends BaseTableStrategy<Organization>
  implements TableStrategy<Organization>
{
  

  protected defaultHeaders(): TableHeader<Organization>[] {
    return [
      { label: 'Name', key: 'name' },
      { label: 'Description', key: 'description' },
      { label: 'Type', key: 'type' },
      { label: 'Updated At', key: 'updated_at', formatter: date },
    ];
  }

  protected defaultActions(): TableAction<Organization>[] {
    return [
      {
        label: 'Edit',
        href: (row: Organization) => route('organization.edit', row.id),
      },
      {
        label: 'Delete',
        css: () => 'text-red-500',
        icon: () => Trash2,
        href: (row: Organization) => route('organization.destroy', row.id),
      },
    ];
  }
}
