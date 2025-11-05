import { User, GitHubConnection } from '$models';

export class GitHubConnectionBase implements GitHubConnection {
    id: number;
    user_id: number;
    user?: User;
    github_user_id: string;
    username: string;
    access_token?: string;
    refresh_token?: string;
    account_handle?: string;
    avatar_url?: string;
    token_expires_at?: Date | string;
    created_at?: Date | string;
    updated_at?: Date | string;


    constructor(data: GitHubConnection) {
        this.id = data.id;
        this.user_id = data.user_id;
        this.github_user_id = data.github_user_id;
        this.username = data.username;
        this.access_token = data.access_token;
        this.refresh_token = data.refresh_token;
        this.account_handle = data.account_handle;
        this.avatar_url = data.avatar_url;
        this.token_expires_at = data.token_expires_at;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}