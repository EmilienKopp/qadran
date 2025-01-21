import { TableAction, TableStrategy } from '$types/common/table';

import { BaseTableStrategy } from '$lib/domain/common/tableStrategy';
import { Organization } from '$models';
import { date } from '$lib/utils/formatting';

export class EmployerOrganizationTableStrategy
  extends BaseTableStrategy<Organization>
  implements TableStrategy<Organization>
{
  defaultHeaders() {
    return [{ label: 'Name', key: 'name' }];
  }

  defaultActions(): TableAction<Organization>[] {
    return [
      {
        label: 'View',
      },
      {
        label: 'Edit',
      },
    ];
  }
}
