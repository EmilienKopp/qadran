import { DefaultOrganizationTableStrategy } from './strategies/defaultOrganizationStrategy';
import { EmployerOrganizationTableStrategy } from './strategies/employerOrganizationTableStrategy';
import { FreelancerOrganizationTableStrategy } from './strategies/freelancerOrganizationStrategy';
import { Organization } from '../../../models';
import { Role } from '$models';
import { TableContext } from '../common/context';
import { TableStrategy } from '$types/components/Table';

export class OrganizationTableContext implements TableContext<Organization> {
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
