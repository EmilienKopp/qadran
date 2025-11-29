import { ClockEntry, DailyLog, Project, User } from '$models';

export class DailyLogBase implements DailyLog {
    user_id?: number;
    user?: User;
    project_id?: number;
    project?: Project;
    notes?: string;
    clock_entry?: ClockEntry | undefined;
    clock_entry_id?: number | undefined;
    name?: string;
    first_name?: string | undefined;
    middle_name?: string | undefined;
    last_name?: string | undefined;
    duration?: string | undefined;
    total_seconds?: number | undefined;
    date?: string | undefined;

    constructor(data: DailyLog) {
        this.user_id = data.user_id;
        this.project_id = data.project_id;
        this.user = data.user;
        this.project = data.project;
        this.clock_entry = data.clock_entry;
        this.clock_entry_id = data.clock_entry_id;
        this.name = data.name;
        this.first_name = data.first_name;
        this.middle_name = data.middle_name;
        this.last_name = data.last_name;
        this.duration = data.duration;
        this.total_seconds = data.total_seconds;
        this.date = data.date;
    }
}