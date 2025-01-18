import { Tag } from '$models';

export class TagBase implements Tag {
    id: number;
    label: string;


    constructor(data: Tag) {
        this.id = data.id;
        this.label = data.label;
    }
}