import type { DataAction, DataHeader, IDataStrategy } from '$types/common/dataDisplay';

import {
  BaseDataDisplayStrategy
} from '$lib/core/strategies/dataDisplayStrategy';
import { Report } from '..';
import { Trash2 } from 'lucide-svelte';
import { readable } from '$lib/utils/formatting';

export class FreelancerReportTableStrategy
  extends BaseDataDisplayStrategy<Report>
  implements IDataStrategy<Report>
{
  defaultHeaders(): DataHeader<Report>[] {
    return [
      { key: 'title', label: 'Title', searchable: true },
      { key: 'status', label: 'Status', searchable: true },
      { key: 'report_type', label: 'Type', searchable: true, formatter: readable },
      { key: 'created_at', label: 'Created', formatter: (value) => new Date(value).toLocaleDateString() },
    ];
  }

  defaultActions(): DataAction<Report>[] {
    return [
      { label: 'Edit', href: (row: Report) => route('report.edit', row.id) },
      { label: 'View', href: (row: Report) => route('report.show', row.id) },
      {
        label: 'Delete',
        callback: Report.delete,
        css: () => 'text-red-500',
        icon: () => Trash2,
      },
    ];
  }
}
