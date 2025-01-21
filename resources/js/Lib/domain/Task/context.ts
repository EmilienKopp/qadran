import { FreelancerTaskTableStrategy } from './strategies/freelancerTaskStrategy';
import { AdminTaskTableStrategy } from './strategies/adminTaskStrategy';
import { EmployerTaskTableStrategy } from './strategies/employerTaskStrategy';
import { Task } from '$models';
import { TableContext } from '../common/context';
import { TableStrategy } from '$types/components/Table';

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