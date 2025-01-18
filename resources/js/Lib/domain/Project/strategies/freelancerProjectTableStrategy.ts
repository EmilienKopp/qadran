import {
  BaseTableStrategy,
  ExtendTableAction,
  ExtendTableHeader,
} from '$lib/domain/common/tableStrategy';
import type { TableAction, TableHeader } from '$types/components/Table';

import { InertiaForm } from '$lib/inertia';
import { Project } from '..';
import { Trash2 } from 'lucide-svelte';

export class FreelancerProjectTableStrategy extends BaseTableStrategy<Project> {
  getHeaders(
    extendedHeaders?: ExtendTableHeader<Project>[]
  ): TableHeader<Project>[] {
    this.headers = [
      { key: 'name', label: 'Name', searchable: true },
      { key: 'description', label: 'Description', searchable: true },
      { key: 'organization.name', label: 'Organization', searchable: true },
    ];
    return this.extendHeaders(extendedHeaders);
  }

  getActions(
    extendedActions?: ExtendTableAction<Project>[]
  ): TableAction<Project>[] {
    this.actions = [
      { label: 'Edit', href: (row: Project) => route('jobs.edit', row.id) },
      {
        label: 'Delete',
        callback: Project.delete,
        css: () => 'text-red-500',
        icon: () => Trash2,
      },
    ];
    return this.extendActions(extendedActions);
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
