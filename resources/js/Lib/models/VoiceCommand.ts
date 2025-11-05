import { User, VoiceCommand } from '$models';

export class VoiceCommandBase implements VoiceCommand {
    id: number;
    user_id: number;
    user?: User;
    transcript: string;
    metadata?: any;
    created_at?: Date | string;
    updated_at?: Date | string;


    constructor(data: VoiceCommand) {
        this.id = data.id;
        this.user_id = data.user_id;
        this.transcript = data.transcript;
        this.metadata = data.metadata;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}