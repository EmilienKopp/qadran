import type { DataAction, DataHeader, IDataStrategy } from '$types/common/dataDisplay';

import {
  BaseDataDisplayStrategy
} from '$lib/core/strategies/dataDisplayStrategy';
import { InertiaForm } from '$lib/inertia';
import { Report } from '..';
import { Trash2 } from 'lucide-svelte';

export class FreelancerReportTableStrategy
  extends BaseDataDisplayStrategy<Report>
  implements IDataStrategy<Report>
{
  defaultHeaders(): DataHeader<Report>[] {
    return [
      { key: 'title', label: 'Title', searchable: true },
      { key: 'created_at', label: 'Created At' },
    ];
  }

  defaultActions(): DataAction<Report>[] {
    return [
      { label: 'Edit', href: (row: Report) => route('report.edit', row.id) },
      {
        label: 'Delete',
        callback: Report.delete,
        css: () => 'text-red-500',
        icon: () => Trash2,
      },
    ];
  }
}
