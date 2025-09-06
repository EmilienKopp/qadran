import { BaseDataDisplayStrategy } from '../../core/strategies/dataDisplayStrategy';
import { Context } from '$lib/core/contexts/context';
import { DefaultReportTableStrategy } from './strategies/defaultReportTableStrategy';
import { FreelancerReportTableStrategy } from './strategies/freelancerReportTableStrategy';
import { Report } from '$models';

export class ReportContext implements Context<Report> {
  strategy: BaseDataDisplayStrategy<Report>;

  constructor(role: string) {
    this.strategy = this.getStrategyForRole(role);
  }

  getStrategyForRole(role: string): BaseDataDisplayStrategy<Report> {
    switch (role) {
      case 'freelancer':
        return new FreelancerReportTableStrategy();
      default:
        return new DefaultReportTableStrategy();
    }
  }
}