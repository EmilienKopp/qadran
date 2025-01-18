import { ClockEntry, ActivityType, Task, ActivityLog } from '$models';

export class ActivityLogBase implements ActivityLog {
    id: number;
    clock_entry_id: number;
    clock_entry?: ClockEntry;
    activity_type_id?: number;
    activity_type?: ActivityType;
    task_id?: number;
    task?: Task;
    start_offset_seconds?: number;
    end_offset_seconds?: number;
    duration_seconds?: number;
    notes?: string;
    created_at?: Date | string;
    updated_at?: Date | string;
    expense_id?: number;


    constructor(data: ActivityLog) {
        this.id = data.id;
        this.clock_entry_id = data.clock_entry_id;
        this.activity_type_id = data.activity_type_id;
        this.task_id = data.task_id;
        this.start_offset_seconds = data.start_offset_seconds;
        this.end_offset_seconds = data.end_offset_seconds;
        this.duration_seconds = data.duration_seconds;
        this.notes = data.notes;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
        this.expense_id = data.expense_id;
    }
}