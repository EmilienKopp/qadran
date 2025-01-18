import { ExtendTableAction, ExtendTableHeader } from '../common/tableStrategy';
import type {
  TableAction,
  TableHeader,
  TableStrategy,
} from '../../../types/components/Table';

import { FreelancerProjectTableStrategy } from './strategies/freelancerProjectTableStrategy';
import { Project } from '$lib/domain/Project';
import type { Role } from '$types/index';
import { TableContext } from '../common/context';

export class ProjectTableContext implements TableContext<Project> {
  strategy: TableStrategy<Project>;

  public constructor(role: Role) {
    this.strategy = this.getStrategyForRole(role);
  }

  public getHeaders(
    extendedHeaders?: ExtendTableHeader<Project>[]
  ): TableHeader<Project>[] {
    return this.strategy.getHeaders(extendedHeaders);
  }

  public getActions(
    extendedActions?: ExtendTableAction<Project>[]
  ): TableAction<Project>[] {
    return this.strategy.getActions(extendedActions);
  }

  public setFilters(
    filters: {
      key: string;
      filterHandler: ((row: Project, form: any) => boolean) | undefined;
    }[]
  ): void {
    if (this.strategy.setFilters) this.strategy.setFilters(filters);
  }

  public getStrategyForRole(role: Role): TableStrategy<Project> {
    switch (role) {
      case 'freelancer':
        return new FreelancerProjectTableStrategy();
      default:
        throw new Error(`Unsupported role: ${role}`);
    }
  }
}
