import { BaseRateDatalistStrategy, BaseRateTableStrategy } from "./baseRateStrategy";

import { DataAction } from "$types/common/dataDisplay";
import { Rate } from "$models";

export class FreelancerRateTableStrategy extends BaseRateTableStrategy {
  protected defaultHeaders() {
    return [
      { label: "Organization", key: "organization.name", searchable: true },
      { label: "Project", key: "project.name", searchable: true },
      ...super.defaultHeaders(),
    ];
  }

  protected defaultActions(): DataAction<Rate>[] {
    return [
      ...super.defaultActions(),
    ];
  }
}

export class FreelancerRateDatalistStrategy extends BaseRateDatalistStrategy {
  protected defaultHeaders() {
    return [
      ...super.defaultHeaders(),
    ];
  }
}
