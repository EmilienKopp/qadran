import { DataAction, IDataStrategy } from "$types/common/dataDisplay";
import { currency, date } from "$lib/utils/formatting";

import { BaseDataDisplayStrategy } from "$lib/core/strategies/dataDisplayStrategy";
import { Rate } from "$models";

export class BaseRateTableStrategy
  extends BaseDataDisplayStrategy<Rate>
  implements IDataStrategy<Rate>
{
  protected defaultHeaders() {
    return [
      { label: "Type", key: "rate_type" },
      { label: "Amount", key: "amount", formatter: currency },
      { label: "Frequency", key: "rate_frequency" },
      { label: "Updated At", key: "updated_at", formatter: date },
    ];
  }

  protected defaultActions(): DataAction<Rate>[] {
    return [
      {
        label: "View",
        href: (row: Rate) => route("rate.show", row.id),
      },
      {
        label: "Edit",
        href: (row: Rate) => route("rate.edit", row.id),
      },
    ];
  }
} 

export class BaseRateDatalistStrategy extends BaseRateTableStrategy {
  protected defaultHeaders() {
    return [
      { label: "Organization", key: "organization.name" },
      { label: "Project", key: "project.name" },
      { label: "Type", key: "rate_type" },
      { label: "Amount", key: "amount", formatter: currency },
      { label: "Frequency", key: "rate_frequency" },
      { label: "Updated At", key: "updated_at", formatter: date },
    ];
  }
}

