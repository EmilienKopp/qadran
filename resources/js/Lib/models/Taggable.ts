import { Taggable, Taggable } from '$models';

export class TaggableBase implements Taggable {
    id: number;
    taggable_type: string;
    taggable_id: number;
    taggable?: Taggable;
    created_at?: Date | string;
    updated_at?: Date | string;


    constructor(data: Taggable) {
        this.id = data.id;
        this.taggable_type = data.taggable_type;
        this.taggable_id = data.taggable_id;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}