import { RepositorySettings } from '$models';

export class RepositorySettingsBase implements RepositorySettings {
    id: number;
    repository_id: number;
    branch_id?: number;
    excluded_folders?: any;
    excluded_file_extensions?: any;
    created_at?: Date | string;
    updated_at?: Date | string;


    constructor(data: RepositorySettings) {
        this.id = data.id;
        this.repository_id = data.repository_id;
        this.branch_id = data.branch_id;
        this.excluded_folders = data.excluded_folders;
        this.excluded_file_extensions = data.excluded_file_extensions;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}