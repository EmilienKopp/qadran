/**
 * Type definitions for Table components
 */

/**
 * Configuration for a table column/header
 */
export interface Header {
    /** Display label for the column header */
    label: string;
    
    /** Key to access data (supports dot notation for nested values) */
    key: string;
    
    /** Optional function to format the displayed value */
    format?: (value: any) => string;
    
    /** Optional function to transform the value before formatting */
    transform?: (value: any) => any;
    
    /** Static href for column links */
    href?: string;
    
    /** Route pattern for dynamic links (will append item.id) */
    route?: string;
    
    /** Display value as a badge */
    asBadge?: boolean;
    
    /** Component to display in popover */
    popoverComponent?: any;
    
    /** Property path for popover data */
    popoverProp?: string;
}

/**
 * Mapping of column keys to popover configurations
 */
export interface PopoverList {
    [key: string]: {
        /** Svelte component to render in popover */
        component: any;
        
        /** Property path to extract popover data from row */
        prop: string;
    };
}

/**
 * Configuration for row action buttons
 */
export interface DropdownAction {
    /** Button label text */
    label: string;
    
    /** Function to execute when button is clicked */
    action?: (item: any) => void;
    
    /** Link href for the action */
    href?: string;
    
    /** Disable the action button */
    disabled?: boolean;
    
    /** Additional CSS classes */
    classes?: string;
}

/**
 * Props for SimpleTable component
 */
export interface SimpleTableProps {
    /** Array of data objects to display in table */
    data?: any[];
    
    /** Title for the table */
    title?: string;
    
    /** Column configuration */
    headers?: Header[];
    
    /** Popover configurations by column key */
    popovers?: PopoverList;
    
    /** Custom CSS classes by column key */
    classes?: { [key: string]: string };
    
    /** Row action buttons */
    actions?: DropdownAction[];
}

/**
 * Props for MonthTable component
 */
export interface MonthTableProps {
    /** Column headers (optional) */
    headers?: string[];
    
    /** 
     * Data object with date keys in YYYY-MM-DD format
     * Each key maps to an array of log/activity objects
     */
    data?: Record<string, any[]>;
    
    /** Current month to display */
    date?: Date | string;
    
    /** Component for rendering inline activity reports */
    ActivityInlineReport?: any;
    
    /** Route prefix for generating navigation URLs */
    routePrefix?: string;
}

/**
 * Structure for monthly data entries
 */
export interface MonthlyData {
    /** Date in YYYY-MM-DD format */
    [date: string]: any[];
}
