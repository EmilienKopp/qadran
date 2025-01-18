import { Role } from '$models';

export class RoleBase implements Role {
    id: number;
    name: string;
    guard_name: string;
    created_at?: Date | string;
    updated_at?: Date | string;


    constructor(data: Role) {
        this.id = data.id;
        this.name = data.name;
        this.guard_name = data.guard_name;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}