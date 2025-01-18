import { Project, Task } from '$models';

export class TaskBase implements Task {
    id: number;
    name: string;
    description?: string;
    completed: boolean;
    project_id: number;
    project?: Project;
    priority: string;
    created_at?: Date | string;
    updated_at?: Date | string;


    constructor(data: Task) {
        this.id = data.id;
        this.name = data.name;
        this.description = data.description;
        this.completed = data.completed;
        this.project_id = data.project_id;
        this.priority = data.priority;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}