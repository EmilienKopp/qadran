import { Industry } from '$models';

export class IndustryBase implements Industry {
    id: number;
    name: string;
    description?: string;
    icon?: string;
    created_at?: Date | string;
    updated_at?: Date | string;


    constructor(data: Industry) {
        this.id = data.id;
        this.name = data.name;
        this.description = data.description;
        this.icon = data.icon;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}