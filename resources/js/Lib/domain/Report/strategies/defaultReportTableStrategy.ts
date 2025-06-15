import { DataAction, IDataStrategy } from '$types/common/dataDisplay';

import { BaseDataDisplayStrategy } from '$lib/core/strategies/dataDisplayStrategy';
import { Report } from '$models';
import { date } from '$lib/utils/formatting';

export class DefaultReportTableStrategy
  extends BaseDataDisplayStrategy<Report>
  implements IDataStrategy<Report>
{
  defaultHeaders() {
    return [
      { label: 'Name', key: 'name' },
      { label: 'Created At', key: 'created_at', formatter: date },
      { label: 'Updated At', key: 'updated_at', formatter: date },
    ];
  }

  defaultActions(): DataAction<Report>[] {
    return [
      {
        label: 'View',
        href: (row: Report) => route('report.show', row.id),
      },
      {
        label: 'Edit',
        href: (row: Report) => route('report.edit', row.id),
      },
    ];
  }
}
