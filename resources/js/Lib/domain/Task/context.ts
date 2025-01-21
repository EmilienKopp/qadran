import { AdminTaskTableStrategy } from './strategies/adminTaskStrategy';
import { EmployerTaskTableStrategy } from './strategies/employerTaskStrategy';
import { FreelancerTaskTableStrategy } from './strategies/freelancerTaskStrategy';
import { TableContext } from '../common/context';
import { TableStrategy } from '$types/common/table';
import { Task } from '$models';

export class TaskTableContext implements TableContext<Task> {
  strategy: TableStrategy<Task>;

  constructor(role: string) {
    this.strategy = this.getStrategyForRole(role);
  }

  getStrategyForRole(role: string): TableStrategy<Task> {
    switch (role) {
      case 'freelancer':
        return new FreelancerTaskTableStrategy();
      case 'admin':
        return new AdminTaskTableStrategy();
      case 'employer':
        return new EmployerTaskTableStrategy();
      default:
        return new freelancerTaskTableStrategy();
    }
  }
}