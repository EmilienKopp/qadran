import { TableAction, TableStrategy } from '$types/common/table';

import { BaseTableStrategy } from '$lib/domain/common/tableStrategy';
import { Organization } from '$models';
import { date } from '$lib/utils/formatting';

export class DefaultOrganizationTableStrategy
  extends BaseTableStrategy<Organization>
  implements TableStrategy<Organization>
{
  defaultHeaders() {
    return [
      { label: 'Name', key: 'name' },
      { label: 'Created At', key: 'created_at', formatter: date },
      { label: 'Updated At', key: 'updated_at', formatter: date },
    ];
  }

  defaultActions(): TableAction<Organization>[] {
    return [];
  }
}
