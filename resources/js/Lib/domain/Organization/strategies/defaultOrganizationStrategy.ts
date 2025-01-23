import { DataAction, IDataStrategy } from '$types/common/dataDisplay';

import { BaseDataDisplayStrategy } from '$lib/core/strategies/dataDisplayStrategy';
import { Organization } from '$models';
import { date } from '$lib/utils/formatting';

export class DefaultOrganizationTableStrategy
  extends BaseDataDisplayStrategy<Organization>
  implements IDataStrategy<Organization>
{
  defaultHeaders() {
    return [
      { label: 'Name', key: 'name' },
      { label: 'Created At', key: 'created_at', formatter: date },
      { label: 'Updated At', key: 'updated_at', formatter: date },
    ];
  }

  defaultActions(): DataAction<Organization>[] {
    return [];
  }
}
