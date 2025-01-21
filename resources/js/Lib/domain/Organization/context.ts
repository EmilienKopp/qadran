import { DefaultOrganizationTableStrategy } from './strategies/defaultOrganizationStrategy';
import { EmployerOrganizationTableStrategy } from './strategies/employerOrganizationTableStrategy';
import { FreelancerOrganizationTableStrategy } from './strategies/freelancerOrganizationStrategy';
import { Organization } from '../../../models';
import { TableContext } from '../common/context';
import { TableStrategy } from '$types/common/table';

export class OrganizationContext implements TableContext<Organization> {
  strategy: TableStrategy<Organization>;

  constructor(role: string) {
    this.strategy = this.getStrategyForRole(role);
  }

  getStrategyForRole(role: string): TableStrategy<Organization> {
    switch (role) {
      case 'freelancer':
        return new FreelancerOrganizationTableStrategy();
      case 'employer':
        return new EmployerOrganizationTableStrategy();
      default:
        return new DefaultOrganizationTableStrategy();
    }
  }
}
