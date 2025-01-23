import { Context } from '../../core/contexts/context';
import { DefaultOrganizationTableStrategy } from './strategies/defaultOrganizationStrategy';
import { EmployerOrganizationTableStrategy } from './strategies/employerOrganizationTableStrategy';
import { FreelancerOrganizationTableStrategy } from './strategies/freelancerOrganizationStrategy';
import { IDataStrategy } from '$types/common/dataDisplay';
import { Organization } from '../../../models';

export class OrganizationContext implements Context<Organization> {
  strategy: IDataStrategy<Organization>;

  constructor(role: string) {
    this.strategy = this.getStrategyForRole(role);
  }

  getStrategyForRole(role: string): IDataStrategy<Organization> {
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
