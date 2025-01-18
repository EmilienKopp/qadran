import { InertiaForm } from "$lib/inertia";

export type TableHeader<T, V = any> = {
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

export type TableAction<T> = {
  label: string;
  callback?: (row: T) => void;
  disabled?: (row: T) => boolean;
  icon?: (row: T) => any;
  css?: (row: T) => string;
  hidden?: (row: T) => boolean;
  href?: (row: T) => string;
};

export interface TableStrategy<T> {
  getHeaders(h: any[] | undefined): TableHeader<T>[];
  getActions(h: any[] | undefined): TableAction[];
  handleRowClick?(model: T): void;
  setFilters?(filters: { key: string; filterHandler: ((row: T, form: InertiaForm<any>) => boolean) | undefined; }[]): void;
}

export type TableConfig<T> = {
  data: T[];
  strategy: TableStrategy<T>;
  headers?: TableHeader<T>[];
  actions?: TableAction<T>[];
  search?: string;
  filters?: { key: string; filterHandler: ((row: T, form: InertiaForm<any>) => boolean) | undefined; }[];
  loading?: boolean;
  error?: string;
};

interface TableProps<T> {
  data?: T[];
  paginated: boolean;
  paginatedData?: Paginated<T>;
  headers: TableHeader<T>[];
  onRowClick?: (row: T) => void;
  onDelete?: (row: T) => void;
  model: 'employer' | 'job' | 'user' | 'application' | 'candidate';
  className?: string;
  actions?: TableAction<T>[];
  searchStrings?: string[];
}