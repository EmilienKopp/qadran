export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    role: Role;
}

export type Role = 'freelancer' | 'employer' | 'admin';

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};
