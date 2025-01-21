import {
  BaseTableStrategy,
  ExtendTableAction,
  ExtendTableHeader,
} from '$lib/domain/common/tableStrategy';
import type { TableAction, TableHeader, TableStrategy } from '$types/common/table';

import { InertiaForm } from '$lib/inertia';
import { Project } from '..';
import { Trash2 } from 'lucide-svelte';

export class FreelancerProjectTableStrategy
  extends BaseTableStrategy<Project>
  implements TableStrategy<Project>
{
  defaultHeaders(): TableHeader<Project>[] {
    return [
      { key: 'name', label: 'Name', searchable: true },
      { key: 'description', label: 'Description', searchable: true },
      { key: 'organization.name', label: 'Organization', searchable: true },
    ];
  }

  defaultActions(): TableAction<Project>[] {
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

  setFilters(
    filters: {
      key: string;
      filterHandler:
        | ((row: Project, form: InertiaForm<any>) => boolean)
        | undefined;
    }[]
  ): void {
    super.setFilters(filters);
  }
}
