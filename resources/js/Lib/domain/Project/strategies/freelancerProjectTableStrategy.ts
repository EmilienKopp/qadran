import type { DataAction, DataHeader, IDataStrategy } from '$types/common/dataDisplay';

import {
  BaseDataDisplayStrategy
} from '$lib/core/strategies/dataDisplayStrategy';
import { InertiaForm } from '$lib/inertia';
import { Project } from '..';
import { Trash2 } from 'lucide-svelte';

export class FreelancerProjectTableStrategy
  extends BaseDataDisplayStrategy<Project>
  implements IDataStrategy<Project>
{
  defaultHeaders(): DataHeader<Project>[] {
    return [
      { key: 'name', label: 'Name', searchable: true },
      { key: 'description', label: 'Description', searchable: true },
      { key: 'organization.name', label: 'Organization', searchable: true },
    ];
  }

  defaultActions(): DataAction<Project>[] {
    return [
      { label: 'Edit', href: (row: Project) => route('project.edit', row.id) },
      {
        label: 'Delete',
        callback: Project.delete,
        css: () => 'text-red-500',
        icon: () => Trash2,
      },
    ];
  }
}
