import Api from './Api'
import KnownIssueWebhookController from './KnownIssueWebhookController'
import KnownIssuesController from './KnownIssuesController'
import ProfileController from './ProfileController'
import McpTokenController from './McpTokenController'
import VoiceAssistantController from './VoiceAssistantController'
import ClockEntryController from './ClockEntryController'
import AudioController from './AudioController'
import ProjectController from './ProjectController'
import OrganizationController from './OrganizationController'
import RateController from './RateController'
import ReportController from './ReportController'
import ActivityController from './ActivityController'
import GitHubOAuthController from './GitHubOAuthController'
import Auth from './Auth'

const Controllers = {
    Api: Object.assign(Api, Api),
    KnownIssueWebhookController: Object.assign(KnownIssueWebhookController, KnownIssueWebhookController),
    KnownIssuesController: Object.assign(KnownIssuesController, KnownIssuesController),
    ProfileController: Object.assign(ProfileController, ProfileController),
    McpTokenController: Object.assign(McpTokenController, McpTokenController),
    VoiceAssistantController: Object.assign(VoiceAssistantController, VoiceAssistantController),
    ClockEntryController: Object.assign(ClockEntryController, ClockEntryController),
    AudioController: Object.assign(AudioController, AudioController),
    ProjectController: Object.assign(ProjectController, ProjectController),
    OrganizationController: Object.assign(OrganizationController, OrganizationController),
    RateController: Object.assign(RateController, RateController),
    ReportController: Object.assign(ReportController, ReportController),
    ActivityController: Object.assign(ActivityController, ActivityController),
    GitHubOAuthController: Object.assign(GitHubOAuthController, GitHubOAuthController),
    Auth: Object.assign(Auth, Auth),
}

export default Controllers