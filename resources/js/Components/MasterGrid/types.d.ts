import type { ColDef } from "ag-grid-community";

export interface MasterGridProps {
  rows: any[];
  columns: any[];
}

interface columnDefs extends ColDef {
  onclick?: (event: any) => void;
  variant?: 'primary' | 'secondary' | 'error' | 'danger' | 'success' | 'warning';
}