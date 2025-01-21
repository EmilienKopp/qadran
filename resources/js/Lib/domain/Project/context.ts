import { DefaultProjectTableStrategy } from './strategies/defaultProjectStrategy';
import { FreelancerProjectTableStrategy } from './strategies/freelancerProjectTableStrategy';
import { Project } from '$models';
import { TableContext } from '../common/context';
import { TableStrategy } from '$types/components/Table';

export class ProjectTableContext implements TableContext<Project> {
  strategy: TableStrategy<Project>;

  constructor(role: string) {
    this.strategy = this.getStrategyForRole(role);
  }

  getStrategyForRole(role: string): TableStrategy<Project> {
    switch (role) {
      case 'freelancer':
        return new FreelancerProjectTableStrategy();
      default:
        return new DefaultProjectTableStrategy();
    }
  }
}