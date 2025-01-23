import { BaseDataDisplayStrategy } from '../../core/strategies/dataDisplayStrategy';
import { Context } from '$lib/core/contexts/context';
import { DefaultProjectTableStrategy } from './strategies/defaultProjectTableStrategy';
import { FreelancerProjectTableStrategy } from './strategies/freelancerProjectTableStrategy';
import { Project } from '$models';

export class ProjectContext implements Context<Project> {
  strategy: BaseDataDisplayStrategy<Project>;

  constructor(role: string) {
    this.strategy = this.getStrategyForRole(role);
  }

  getStrategyForRole(role: string): BaseDataDisplayStrategy<Project> {
    switch (role) {
      case 'freelancer':
        return new FreelancerProjectTableStrategy();
      default:
        return new DefaultProjectTableStrategy();
    }
  }
}