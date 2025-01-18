import { TaskCategory } from '$models';

export class TaskCategoryBase implements TaskCategory {
    id: number;
    name: string;
    description?: string;
    created_at?: Date | string;
    updated_at?: Date | string;


    constructor(data: TaskCategory) {
        this.id = data.id;
        this.name = data.name;
        this.description = data.description;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}