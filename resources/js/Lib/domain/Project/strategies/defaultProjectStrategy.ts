import { TableAction, TableStrategy } from '$types/common/table';

import { BaseTableStrategy } from '$lib/domain/common/tableStrategy';
import { Project } from '$models';
import { date } from '$lib/utils/formatting';

export class DefaultProjectTableStrategy
  extends BaseTableStrategy<Project>
  implements TableStrategy<Project>
{
  defaultHeaders() {
    return [
      { label: 'Name', key: 'name' },
      { label: 'Created At', key: 'created_at', formatter: date },
      { label: 'Updated At', key: 'updated_at', formatter: date },
    ];
  }

  defaultActions(): TableAction<Project>[] {
    return [
      {
        label: 'View',
        href: (row: Project) => route('project.show', row.id),
      },
      {
        label: 'Edit',
        href: (row: Project) => route('project.edit', row.id),
      },
    ];
  }
}
