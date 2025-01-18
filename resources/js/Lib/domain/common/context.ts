import { Role } from "$types/index";
import { TableStrategy } from "$types/components/Table";

export interface TableContext<T> {
  strategy: TableStrategy<T>;
  getStrategyForRole(role: Role): TableStrategy<T>;
}
