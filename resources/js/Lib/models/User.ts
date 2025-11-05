import { User } from '$models';

export class UserBase implements User {
    id: number;
    email: string;
    email_verified_at?: Date | string;
    password: string;
    remember_token?: string;
    created_at?: Date | string;
    updated_at?: Date | string;
    handle: string;
    first_name: string;
    middle_name?: string;
    last_name: string;
    title?: string;
    phone_main?: string;
    phone_secondary?: string;
    bio?: string;
    avatar?: string;
    role_data?: any;
    facebook?: string;
    x_twitter?: string;
    instagram?: string;
    linkedin?: string;
    youtube?: string;
    website?: string;
    github?: string;
    dashboard_preferences?: any;
    workos_id?: string;


    constructor(data: User) {
        this.id = data.id;
        this.email = data.email;
        this.email_verified_at = data.email_verified_at;
        this.password = data.password;
        this.remember_token = data.remember_token;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
        this.handle = data.handle;
        this.first_name = data.first_name;
        this.middle_name = data.middle_name;
        this.last_name = data.last_name;
        this.title = data.title;
        this.phone_main = data.phone_main;
        this.phone_secondary = data.phone_secondary;
        this.bio = data.bio;
        this.avatar = data.avatar;
        this.role_data = data.role_data;
        this.facebook = data.facebook;
        this.x_twitter = data.x_twitter;
        this.instagram = data.instagram;
        this.linkedin = data.linkedin;
        this.youtube = data.youtube;
        this.website = data.website;
        this.github = data.github;
        this.dashboard_preferences = data.dashboard_preferences;
        this.workos_id = data.workos_id;
    }
}