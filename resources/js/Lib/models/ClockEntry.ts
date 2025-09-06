import { User, Project, Rate, ClockEntry } from '$models';

export class ClockEntryBase implements ClockEntry {
    id: number;
    user_id: number;
    user?: User;
    project_id: number;
    project?: Project;
    in?: any;
    out?: any;
    timezone?: string;
    notes?: string;
    created_at?: Date | string;
    updated_at?: Date | string;
    duration_seconds?: number;
    rate_id?: number;
    rate?: Rate;
    applied_rate?: number;
    currency: string;
    amount?: number;


    constructor(data: ClockEntry) {
        this.id = data.id;
        this.user_id = data.user_id;
        this.project_id = data.project_id;
        this.in = data.in;
        this.out = data.out;
        this.timezone = data.timezone;
        this.notes = data.notes;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
        this.duration_seconds = data.duration_seconds;
        this.rate_id = data.rate_id;
        this.applied_rate = data.applied_rate;
        this.currency = data.currency;
        this.amount = data.amount;
    }
}