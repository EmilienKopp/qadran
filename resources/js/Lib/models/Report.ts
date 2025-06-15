import { User, Report } from '$models';

export class ReportBase implements Report {
    id: number;
    user_id: number;
    user?: User;
    title?: string;
    content?: string;
    report_type: string;
    original_log?: string;
    aggregated_diff?: string;
    created_at?: Date | string;
    updated_at?: Date | string;


    constructor(data: Report) {
        this.id = data.id;
        this.user_id = data.user_id;
        this.title = data.title;
        this.content = data.content;
        this.report_type = data.report_type;
        this.original_log = data.original_log;
        this.aggregated_diff = data.aggregated_diff;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}