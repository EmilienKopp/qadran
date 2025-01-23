import { InertiaForm } from "$lib/inertia";
import { Paginated } from "$types/pagination";

/**
 * A type that extends a given type T with an optional index property.
 */
export type Indexed<T> = T & { index?: number };

/**
 * A type that extends TableHeader with an optional index property.
 */
export type ExtendedHeader<T> = Indexed<DataHeader<T>>;

/**
 * A type that extends TableAction with an optional index property.
 */
export type ExtendedAction<T> = Indexed<DataAction<T>>;

export type DataHeader<T, V = any> = {
  key: string;
  label: string;
  formatter?: (value: V) => string;
  combined?: (row: T) => string;
  icon?: (row: T) => any;
  iconOnly?: boolean;
  iconClass?: (row: T) => string;
  searchable?: boolean;
  filterHandler?: (row: T, form: InertiaForm<any>) => boolean;
};

export type DataAction<T> = {
  label: string;
  callback?: (row: T) => void;
  disabled?: (row: T) => boolean;
  icon?: (row: T) => any;
  css?: (row: T) => string;
  hidden?: (row: T) => boolean;
  href?: (row: T) => string;
  position?: number;
};

export interface IDataStrategy<T> {
  headers(h?: DataAction<T>[] | undefined): DataHeader<T>[];
  actions(h?: DataAction<T>[] | undefined): DataAction<T>[];
  handleRowClick?(model: T): void;
  setFilters?(
    filters: {
      key: string;
      filterHandler: ((row: T, form: InertiaForm<any>) => boolean) | undefined;
    }[]
  ): void;
}

export type TableConfig<T> = {
  data: T[];
  strategy: IDataStrategy<T>;
  headers?: DataHeader<T>[];
  actions?: DataAction<T>[];
  search?: string;
  filters?: { key: string; filterHandler: ((row: T, form: InertiaForm<any>) => boolean) | undefined; }[];
  loading?: boolean;
  error?: string;
};

export interface ITableProps<T> {
  data?: T[];
  paginated: boolean;
  paginatedData?: Paginated<T>;
  headers: DataHeader<T>[];
  onRowClick?: (row: T) => void;
  onDelete?: (row: T) => void;
  model: 'employer' | 'job' | 'user' | 'application' | 'candidate';
  className?: string;
  actions?: DataAction<T>[];
  searchStrings?: string[];
}