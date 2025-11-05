# Tables Components

This directory contains reusable table components adapted for Svelte 5 from the legacy codebase.

## Components

### SimpleTable

A flexible, generic table component for displaying tabular data with customizable headers, formatting, and actions.

#### Features
- Dynamic column configuration with headers
- Custom value formatting and transformation
- Route-based and href-based links
- Badge display support
- Row actions (buttons)
- Popover support (placeholder for future implementation)
- Nested value resolution using dot notation

#### Props

```typescript
interface Props {
    data?: any[];              // Array of data objects to display
    title?: string;            // Table title (default: "Data Table")
    headers?: Header[];        // Column configuration
    popovers?: PopoverList;    // Popover components for columns
    classes?: Record<string, string>;  // Custom classes per column
    actions?: DropdownAction[]; // Row action buttons
}
```

#### Header Type

```typescript
type Header = {
    label: string;           // Column header label
    key: string;             // Object key (supports dot notation, e.g., 'user.name')
    format?: (value: any) => string;     // Format the value for display
    transform?: (value: any) => any;     // Transform value before formatting
    href?: string;           // Static link
    route?: string;          // Route pattern (uses item.id)
    asBadge?: boolean;       // Display as a badge
    popoverComponent?: any;  // Component to show in popover
    popoverProp?: string;    // Property path for popover data
};
```

#### Usage Example

```svelte
<script lang="ts">
    import { SimpleTable } from '$components/Tables';
    import type { Header, DropdownAction } from '$components/Tables';

    const headers: Header[] = [
        { label: 'Name', key: 'name', route: 'users' },
        { label: 'Email', key: 'email' },
        { label: 'Status', key: 'status', asBadge: true },
        { 
            label: 'Created', 
            key: 'created_at',
            format: (date) => new Date(date).toLocaleDateString()
        }
    ];

    const actions: DropdownAction[] = [
        { label: 'Edit', href: '/edit' },
        { label: 'Delete', action: (item) => deleteItem(item.id) }
    ];

    const users = [
        { id: 1, name: 'John Doe', email: 'john@example.com', status: 'active', created_at: '2024-01-01' },
        { id: 2, name: 'Jane Smith', email: 'jane@example.com', status: 'inactive', created_at: '2024-01-15' }
    ];
</script>

<SimpleTable 
    data={users} 
    {headers} 
    {actions}
    title="User Management"
/>
```

---

### MonthTable

A specialized calendar-style table component for displaying monthly data with date-based rows.

#### Features
- Calendar month view with individual date rows
- Navigation between months
- Expandable/collapsible row details
- Scroll to latest entry with data
- Scroll to top functionality
- Bulk expand/collapse all rows
- Custom activity inline report component support
- Context menu support (placeholder)

#### Props

```typescript
interface Props {
    headers?: string[];           // Column headers (optional)
    data?: any;                   // Object with date keys (YYYY-MM-DD) mapping to log arrays
    date?: Date | string;         // Current month date
    ActivityInlineReport?: any;   // Component for rendering activity details
    routePrefix?: string;         // Route prefix for navigation (default: 'activities')
}
```

#### Data Structure

```typescript
// Expected data format
const data = {
    '2024-01-01': [
        { id: 1, title: 'Activity 1', description: '...' },
        { id: 2, title: 'Activity 2', description: '...' }
    ],
    '2024-01-15': [
        { id: 3, title: 'Activity 3', description: '...' }
    ],
    // ... more dates
};
```

#### Usage Example

```svelte
<script lang="ts">
    import { MonthTable } from '$components/Tables';
    import ActivityInlineReport from '$components/Activity/ActivityInlineReport.svelte';

    const currentDate = new Date('2024-01-15');
    
    const activityData = {
        '2024-01-01': [
            { id: 1, title: 'Morning Meeting', duration: 60 },
            { id: 2, title: 'Code Review', duration: 45 }
        ],
        '2024-01-02': [
            { id: 3, title: 'Development', duration: 180 }
        ]
    };
</script>

<MonthTable 
    date={currentDate}
    data={activityData}
    ActivityInlineReport={ActivityInlineReport}
    routePrefix="activities"
/>
```

## Key Differences from Legacy

### Svelte 5 Adaptations

1. **Runes Mode**: Uses `$state`, `$derived`, and `$props` instead of reactive declarations
2. **Module Context**: Changed from `context="module"` to `module` attribute
3. **Component Rendering**: Removed `<svelte:component>` in favor of direct component binding
4. **Props Interface**: Uses TypeScript `interface Props` with `$props()` destructuring

### Architecture Changes

1. **Button Component**: Uses new `Button` component from `$components/Actions` instead of `MiniButton`
2. **Routing**: Simplified to use direct href instead of Laravel Ziggy routes (can be adapted as needed)
3. **Styling**: Maintained DaisyUI classes for consistency
4. **Utilities**: Uses centralized utility functions from `$lib/utils`

## Dependencies

- `dayjs` - Date manipulation (MonthTable)
- `@inertiajs/svelte` - Router for navigation (MonthTable)
- `tailwind-merge` - Class merging utility (SimpleTable)
- `clsx` - Conditional classes (via Button component)

## Future Enhancements

- [ ] Implement full popover functionality in SimpleTable
- [ ] Add sorting capability to SimpleTable
- [ ] Add filtering/search functionality
- [ ] Add pagination support
- [ ] Implement context menu actions in MonthTable
- [ ] Add export functionality (CSV, PDF)
- [ ] Add column visibility toggle
- [ ] Add responsive mobile view optimization
