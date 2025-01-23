import { INavigationStrategy, NavigationElement } from "$types/common/navigation";

import { Context } from "./context";
import { DefaultNavigationStrategy } from "../strategies/navigationStrategy";
import { FreelancerNavigationStrategy } from "$lib/domain/navigation/strategies/freelancerNavigationStrategy";

export class NavigationContext implements Context<NavigationElement> {
  strategy: INavigationStrategy<NavigationElement>;

  constructor(role: string) {
    this.strategy = this.getStrategyForRole(role);
  }

  getStrategyForRole(role: string): INavigationStrategy<NavigationElement> {
    switch (role) {
      case 'freelancer':
        return new FreelancerNavigationStrategy();
      default:
        return new DefaultNavigationStrategy();
    }
  }
}
