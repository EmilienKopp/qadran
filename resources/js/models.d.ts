export interface ActivityLog {
    id: number;
    clock_entry_id: number;
    clock_entry?: ClockEntry;
    activity_type_id?: number;
    activity_type?: ActivityType;
    task_id?: number;
    task?: Task;
    start_offset_seconds?: number;
    end_offset_seconds?: number;
    duration_seconds?: number;
    notes?: string;
    created_at?: Date | string;
    updated_at?: Date | string;
    expense_id?: number;
}

export interface ActivityType {
    id: number;
    name: string;
    description?: string;
    color?: string;
    icon?: string;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export interface ClockEntry {
    id: number;
    user_id: number;
    user?: User;
    project_id: number;
    project?: Project;
    in?: any;
    out?: any;
    timezone?: string;
    notes?: string;
    created_at?: Date | string;
    updated_at?: Date | string;
    duration_seconds?: number;
    rate_id?: number;
    applied_rate?: number;
    currency: string;
    amount?: number;
}

export interface Industry {
    id: number;
    name: string;
    description?: string;
    icon?: string;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export interface Organization {
    id: number;
    user_id?: number;
    user?: User;
    name: string;
    description?: string;
    type: string;
    icon?: string;
    metadata?: any;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export interface OrganizationUser {
    id: number;
    user_id: number;
    user?: User;
    organization_id: number;
    organization?: Organization;
    elevated: boolean;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export interface Project {
    id: number;
    organization_id: number;
    organization?: Organization;
    name: string;
    description?: string;
    type: string;
    status: string;
    start_date?: Date | string;
    end_date?: Date | string;
    location?: string;
    icon?: string;
    default_break_duration_seconds?: string;
    metadata?: any;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export interface ProjectUser {
    id: number;
    project_id: number;
    project?: Project;
    user_id: number;
    user?: User;
    roles: any;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export interface Role {
    id: number;
    name: string;
    guard_name: string;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export interface Tag {
    id: number;
    label: string;
}

export interface Taggable {
    id: number;
    taggable_type: string;
    taggable_id: number;
    taggable?: Taggable;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export interface Task {
    id: number;
    name: string;
    description?: string;
    completed: boolean;
    project_id: number;
    project?: Project;
    priority: string;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export interface TaskCategoriesAlias {

}

export interface TaskCategory {
    id: number;
    name: string;
    description?: string;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export interface TaskCategoryAlias {
    id: number;
    alias: string;
    task_category_id: number;
    task_category?: TaskCategory;
    organization_id?: number;
    organization?: Organization;
    user_id?: number;
    user?: User;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export interface User {
    id: number;
    email: string;
    email_verified_at?: Date | string;
    password: string;
    remember_token?: string;
    created_at?: Date | string;
    updated_at?: Date | string;
    handle: string;
    first_name: string;
    middle_name?: string;
    last_name: string;
    title?: string;
    phone_main?: string;
    phone_secondary?: string;
    bio?: string;
    avatar?: string;
    role_data?: any;
    facebook?: string;
    x_twitter?: string;
    instagram?: string;
    linkedin?: string;
    youtube?: string;
    website?: string;
    github?: string;
    dashboard_preferences?: any;
    roles?: Role[];
    organizations?: Organization[];
    todays_entries?: ClockEntry[];
    projects?: Project[];
}

export interface Rate {
    id: number;
    rate_type_id: number;
    rate_type?: RateType;
    rate_frequency: string;
    organization_id?: number;
    organization?: Organization;
    project_id?: number;
    project?: Project;
    user_id?: number;
    user?: User;
    amount: number;
    currency: string;
    overtime_multiplier: number;
    holiday_multiplier: number;
    special_multiplier: number;
    custom_multiplier_rate?: number;
    custom_multiplier_label?: string;
    is_default: boolean;
    effective_from?: Date | string;
    effective_until?: Date | string;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export interface RateType {
    id: number;
    name: string;
    description?: string;
    scope: string;
    organization_id?: number;
    organization?: Organization;
    project_id?: number;
    project?: Project;
    user_id?: number;
    user?: User;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export type ModelTypes = ActivityLog | ActivityType | ClockEntry | Industry | Organization | OrganizationUser | Project | ProjectUser | Role | Tag | Taggable | Task | TaskCategoriesAlias | TaskCategory | TaskCategoryAlias | User | Rate | RateType;