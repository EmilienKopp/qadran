import ArtisanController from './ArtisanController'
import UserController from './UserController'
import OrganizationController from './OrganizationController'
import ProjectController from './ProjectController'
import ReportController from './ReportController'
import TenantController from './TenantController'
import ClockEntryController from './ClockEntryController'
import TaskController from './TaskController'
import N8NController from './N8NController'

const Api = {
    ArtisanController: Object.assign(ArtisanController, ArtisanController),
    UserController: Object.assign(UserController, UserController),
    OrganizationController: Object.assign(OrganizationController, OrganizationController),
    ProjectController: Object.assign(ProjectController, ProjectController),
    ReportController: Object.assign(ReportController, ReportController),
    TenantController: Object.assign(TenantController, TenantController),
    ClockEntryController: Object.assign(ClockEntryController, ClockEntryController),
    TaskController: Object.assign(TaskController, TaskController),
    N8NController: Object.assign(N8NController, N8NController),
}

export default Api