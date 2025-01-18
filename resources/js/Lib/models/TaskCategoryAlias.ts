import { TaskCategory, Organization, User, TaskCategoryAlias } from '$models';

export class TaskCategoryAliasBase implements TaskCategoryAlias {
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


    constructor(data: TaskCategoryAlias) {
        this.id = data.id;
        this.alias = data.alias;
        this.task_category_id = data.task_category_id;
        this.organization_id = data.organization_id;
        this.user_id = data.user_id;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}