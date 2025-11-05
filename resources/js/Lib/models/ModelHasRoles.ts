import { Role, ModelHasRoles } from '$models';

export class ModelHasRolesBase implements ModelHasRoles {
    role_id: number;
    role?: Role;
    model_type: string;
    model_id: number;


    constructor(data: ModelHasRoles) {
        this.role_id = data.role_id;
        this.model_type = data.model_type;
        this.model_id = data.model_id;
    }
}