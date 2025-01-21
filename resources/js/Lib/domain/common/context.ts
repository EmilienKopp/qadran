import { TableStrategy } from "$types/common/table";

export interface TableContext<T> {
  strategy: TableStrategy<T>;
  getStrategyForRole(role: string): TableStrategy<T>;
}
