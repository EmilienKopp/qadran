import { ActivityType } from '$models';

export class ActivityTypeBase implements ActivityType {
    id: number;
    name: string;
    description?: string;
    color?: string;
    icon?: string;
    created_at?: Date | string;
    updated_at?: Date | string;


    constructor(data: ActivityType) {
        this.id = data.id;
        this.name = data.name;
        this.description = data.description;
        this.color = data.color;
        this.icon = data.icon;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}