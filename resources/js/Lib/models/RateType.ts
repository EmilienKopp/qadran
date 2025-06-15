import { Organization, Project, User, RateType } from '$models';

export class RateTypeBase implements RateType {
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


    constructor(data: RateType) {
        this.id = data.id;
        this.name = data.name;
        this.description = data.description;
        this.scope = data.scope;
        this.organization_id = data.organization_id;
        this.project_id = data.project_id;
        this.user_id = data.user_id;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}