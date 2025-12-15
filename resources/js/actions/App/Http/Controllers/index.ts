import Api from './Api'
import KnownIssuesController from './KnownIssuesController'
import PrivacyPolicyController from './PrivacyPolicyController'
import Auth from './Auth'
import GitHubOAuthController from './GitHubOAuthController'
import GoogleOAuthController from './GoogleOAuthController'
import KnownIssueWebhookController from './KnownIssueWebhookController'
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
import ActivityLogController from './ActivityLogController'

const Controllers = {
    Api: Object.assign(Api, Api),
    KnownIssuesController: Object.assign(KnownIssuesController, KnownIssuesController),
    PrivacyPolicyController: Object.assign(PrivacyPolicyController, PrivacyPolicyController),
    Auth: Object.assign(Auth, Auth),
    GitHubOAuthController: Object.assign(GitHubOAuthController, GitHubOAuthController),
    GoogleOAuthController: Object.assign(GoogleOAuthController, GoogleOAuthController),
    KnownIssueWebhookController: Object.assign(KnownIssueWebhookController, KnownIssueWebhookController),
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
    ActivityLogController: Object.assign(ActivityLogController, ActivityLogController),
}

export default Controllers