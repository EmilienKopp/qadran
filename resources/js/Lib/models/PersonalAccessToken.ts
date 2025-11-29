import { PersonalAccessToken } from '$models';

export class PersonalAccessTokenBase implements PersonalAccessToken {
    id: number;
    tokenable_type: string;
    tokenable_id: number;
    name: string;
    token: string;
    abilities?: string;
    last_used_at?: Date | string;
    expires_at?: Date | string;
    created_at?: Date | string;
    updated_at?: Date | string;


    constructor(data: PersonalAccessToken) {
        this.id = data.id;
        this.tokenable_type = data.tokenable_type;
        this.tokenable_id = data.tokenable_id;
        this.name = data.name;
        this.token = data.token;
        this.abilities = data.abilities;
        this.last_used_at = data.last_used_at;
        this.expires_at = data.expires_at;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}