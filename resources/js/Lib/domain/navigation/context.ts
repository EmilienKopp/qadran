import { INavigationStrategy, NavigationElement } from "$types/common/navigation";

import { Context } from "../../core/contexts/context";
import { DefaultNavigationStrategy } from '$lib/core/strategies/navigationStrategy';
import { FreelancerNavigationStrategy } from './strategies/freelancerNavigationStrategy';

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
