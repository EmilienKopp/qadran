import { RateType, Organization, Project, User, Rate } from '$models';

export class RateBase implements Rate {
    id: number;
    rate_type_id: number;
    rate_type?: RateType;
    rate_frequency: string;
    organization_id?: number;
    organization?: Organization;
    project_id?: number;
    project?: Project;
    user_id?: number;
    user?: User;
    amount: number;
    currency: string;
    overtime_multiplier: number;
    holiday_multiplier: number;
    special_multiplier: number;
    custom_multiplier_rate?: number;
    custom_multiplier_label?: string;
    is_default: boolean;
    effective_from?: Date | string;
    effective_until?: Date | string;
    created_at?: Date | string;
    updated_at?: Date | string;


    constructor(data: Rate) {
        this.id = data.id;
        this.rate_type_id = data.rate_type_id;
        this.rate_frequency = data.rate_frequency;
        this.organization_id = data.organization_id;
        this.project_id = data.project_id;
        this.user_id = data.user_id;
        this.amount = data.amount;
        this.currency = data.currency;
        this.overtime_multiplier = data.overtime_multiplier;
        this.holiday_multiplier = data.holiday_multiplier;
        this.special_multiplier = data.special_multiplier;
        this.custom_multiplier_rate = data.custom_multiplier_rate;
        this.custom_multiplier_label = data.custom_multiplier_label;
        this.is_default = data.is_default;
        this.effective_from = data.effective_from;
        this.effective_until = data.effective_until;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}