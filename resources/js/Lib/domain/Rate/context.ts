import { FreelancerRateDatalistStrategy, FreelancerRateTableStrategy } from "./strategies/freelancerRateStrategy";

import { AdminRateTableStrategy } from "./strategies/adminRateStrategy";
import { BaseRateTableStrategy } from "./strategies/baseRateStrategy";
import { Context } from "../../core/contexts/context";
import { IDataStrategy } from "$types/common/dataDisplay";
import { Rate } from "$models";

export class RateTableContext implements Context<Rate> {
  strategy: IDataStrategy<Rate>;

  constructor(role: string) {
    this.strategy = this.getStrategyForRole(role);
  }

  getStrategyForRole(role: string): IDataStrategy<Rate> {
    switch (role) {
      case "admin":
        return new AdminRateTableStrategy();
      case "freelancer":
        return new FreelancerRateTableStrategy();
      default:
        return new BaseRateTableStrategy();
    }
  }
}

export class RateDatalistContext implements Context<Rate> {
  strategy: IDataStrategy<Rate>;

  constructor(role: string) {
    this.strategy = this.getStrategyForRole(role);
  }
  
  getStrategyForRole(role: string): IDataStrategy<Rate> {
    switch (role) {
      case "freelancer":
        return new FreelancerRateDatalistStrategy();
      default:
        return new BaseRateTableStrategy();
    }
  }
}
