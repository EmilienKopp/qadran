import { DataAction, IDataStrategy } from '$types/common/dataDisplay';

import { BaseDataDisplayStrategy } from '$lib/core/strategies/dataDisplayStrategy';
import { Organization } from '$models';
import { date } from '$lib/utils/formatting';

export class EmployerOrganizationTableStrategy
  extends BaseDataDisplayStrategy<Organization>
  implements IDataStrategy<Organization>
{
  defaultHeaders() {
    return [{ label: 'Name', key: 'name' }];
  }

  defaultActions(): DataAction<Organization>[] {
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
