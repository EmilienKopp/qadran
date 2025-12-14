export interface Activity {
    id: number;
    user_id: number;
    user?: User;
    project_id: number;
    project?: Project;
    task_category_id: number;
    task_category?: TaskCategory;
    date: Date;
    duration: number;
    notes?: string;
    created_at?: Date | string;
    updated_at?: Date | string;
    user?: User;
    project?: Project;
    taskCategory?: TaskCategory;
}

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
    clockEntry?: ClockEntry;
    activityType?: ActivityType;
    task?: Task;
}

export interface ActivityType {
    id: number;
    name: string;
    description?: string;
    color?: string;
    icon?: string;
    created_at?: Date | string;
    updated_at?: Date | string;
    activities?: ActivityLog[];
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
    rate?: Rate;
    applied_rate?: number;
    currency: string;
    amount?: number;
    date?: Date;
    project?: Project;
    user?: User;
    activityLogs?: ActivityLog[];
}

export interface Currency {
    id: number;
    code: string;
    name: string;
    symbol: string;
    symbol_first: boolean;
    is_default: boolean;
    exchange_rate: number;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export interface GitHubConnection {
    id: number;
    user_id: number;
    user?: User;
    github_user_id: string;
    username: string;
    access_token?: string;
    refresh_token?: string;
    account_handle?: string;
    avatar_url?: string;
    token_expires_at?: Date | string;
    created_at?: Date | string;
    updated_at?: Date | string;
    user?: User;
}

export interface Industry {
    id: number;
    name: string;
    description?: string;
    icon?: string;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export interface ModelHasRoles {
    role_id: number;
    role?: Role;
    model_type: string;
    model_id: number;
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
    projects?: Project[];
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

export interface Permission {
    id: number;
    name: string;
    guard_name: string;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export interface PersonalAccessToken {
    id: number;
    tokenable_type: string;
    tokenable_id: number;
    name: string;
    token: string;
    abilities?: string;
    last_used_at?: Date | string;
    expires_at?: Date | string;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export interface Project {
    id: number;
    organization_id?: number;
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
    deleted_at?: Date | string;
    entries?: ClockEntry[];
    tasks?: Task[];
    organization?: Organization;
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

export interface Rate {
    id: number;
    rate_type: string;
    rate_frequency: string;
    organization_id?: number;
    organization?: Organization;
    project_id?: number;
    project?: Project;
    user_id?: number;
    user?: User;
    amount: number;
    currency: string;
    overtime_multiplier?: number;
    holiday_multiplier?: number;
    special_multiplier?: number;
    custom_multiplier_rate?: number;
    custom_multiplier_label?: string;
    is_default?: boolean;
    effective_from?: Date | string;
    effective_until?: Date | string;
    created_at?: Date | string;
    updated_at?: Date | string;
}

export interface Report {
    id: number;
    user_id: number;
    user?: User;
    project_id?: number;
    project?: Project;
    organization_id?: number;
    organization?: Organization;
    repository_id?: number;
    branch_id?: number;
    title?: string;
    content?: string;
    report_type: string;
    original_log?: string;
    aggregated_diff?: string;
    created_at?: Date | string;
    updated_at?: Date | string;
    user?: User;
}

export interface RepositorySettings {
    id: number;
    repository_id: number;
    branch_id?: number;
    excluded_folders?: any;
    excluded_file_extensions?: any;
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
    project?: Project;
}

export interface TaskCategoriesAlias {

}

export interface TaskCategory {
    id: number;
    name: string;
    description?: string;
    created_at?: Date | string;
    updated_at?: Date | string;
    tasks?: Task[];
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
    workos_id?: string;
    timezone?: string;
    n8n_config?: any;
    roles?: Role[];
    clockEntries?: ClockEntry[];
    reports?: Report[];
    voiceCommands?: VoiceCommand[];
}

export interface VoiceCommand {
    id: number;
    user_id: number;
    user?: User;
    transcript: string;
    parsed_command?: any;
    metadata?: any;
    created_at?: Date | string;
    updated_at?: Date | string;
    user?: User;
}

export interface DailyLog {
    user_id?: number;
    user?: User;
    clock_entry_id?: number;
    clock_entry?: ClockEntry;
    name?: string;
    first_name?: string;
    middle_name?: string;
    last_name?: string;
    project_id?: number;
    project?: Project;
    project_name?: string;
    duration?: string;
    date?: string;
    total_seconds?: number;
    activities?: ActivityLog[];
}

export type ModelTypes = Activity | ActivityLog | ActivityType | ClockEntry | Currency | GitHubConnection | Industry | ModelHasRoles | Organization | OrganizationUser | Permission | PersonalAccessToken | Project | ProjectUser | Rate | Report | RepositorySettings | Role | Tag | Taggable | Task | TaskCategoriesAlias | TaskCategory | TaskCategoryAlias | User | VoiceCommand | DailyLog;