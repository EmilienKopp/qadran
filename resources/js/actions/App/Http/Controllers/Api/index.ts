import ArtisanController from './ArtisanController'
import RepositoryProxyController from './RepositoryProxyController'
import UserController from './UserController'
import OrganizationController from './OrganizationController'
import ProjectController from './ProjectController'
import ReportController from './ReportController'
import TenantController from './TenantController'
import ClockEntryController from './ClockEntryController'
import TaskController from './TaskController'

const Api = {
    ArtisanController: Object.assign(ArtisanController, ArtisanController),
    RepositoryProxyController: Object.assign(RepositoryProxyController, RepositoryProxyController),
    UserController: Object.assign(UserController, UserController),
    OrganizationController: Object.assign(OrganizationController, OrganizationController),
    ProjectController: Object.assign(ProjectController, ProjectController),
    ReportController: Object.assign(ReportController, ReportController),
    TenantController: Object.assign(TenantController, TenantController),
    ClockEntryController: Object.assign(ClockEntryController, ClockEntryController),
    TaskController: Object.assign(TaskController, TaskController),
}

export default Api