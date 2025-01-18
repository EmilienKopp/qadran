import type { TableAction, TableHeader } from "$types/components/Table";

export type Indexed<T> = T & { index?: number };

export type ExtendTableHeader<T> = Indexed<TableHeader<T>>;

export type ExtendTableAction<T> = Indexed<TableAction<T>>;

export class BaseTableStrategy<T> {

  constructor(
    protected headers: TableHeader<T>[] = [],
    protected actions: TableAction<T>[] = []
  ) { }

  getHeaders(extendedHeaders: ExtendTableHeader<T>[] = []): TableHeader<T>[] {
    return this.extendHeaders(extendedHeaders);
  }

  getActions(extendedActions: ExtendTableAction<T>[] = []): TableAction<T>[] {
    return this.extendActions(extendedActions);
  }

  extendHeaders(headers: ExtendTableHeader<T>[] = []) {
    for (const header of headers) {
      const headerIndex = this.headers.findIndex(h => h.key === header.key);
      if (headerIndex !== -1) {
        this.headers[headerIndex] = {
          ...this.headers[headerIndex],
          ...header
        }
        continue;
      }
      this.headers.splice(header.index ?? 0, 0, header);
    }
    return this.headers;
  }

  extendActions(actions: ExtendTableAction<T>[] = []) {
    for (const action of actions) {
      const actionIndex = this.actions.findIndex(a => a.label === action.label);
      if (actionIndex !== -1) {
        this.actions[actionIndex] = {
          ...this.actions[actionIndex],
          ...action
        }
        continue;
      }
      this.actions.splice(action.index ?? 0, 0, action);
    }
    return this.actions;
  }

  setFilters(filters: {key: string, filterHandler: TableHeader<T>['filterHandler']}[]) {
    for (const filter of filters) {
      const filterIndex = this.headers.findIndex(h => h.key === filter.key);
      if (filterIndex !== -1) {
        this.headers[filterIndex].filterHandler = filter.filterHandler;
      }
    }
  }
}