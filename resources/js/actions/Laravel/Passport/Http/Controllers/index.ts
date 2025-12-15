import AuthorizationController from './AuthorizationController'
import ApproveAuthorizationController from './ApproveAuthorizationController'
import DenyAuthorizationController from './DenyAuthorizationController'
import AccessTokenController from './AccessTokenController'
import TransientTokenController from './TransientTokenController'

const Controllers = {
    AuthorizationController: Object.assign(AuthorizationController, AuthorizationController),
    ApproveAuthorizationController: Object.assign(ApproveAuthorizationController, ApproveAuthorizationController),
    DenyAuthorizationController: Object.assign(DenyAuthorizationController, DenyAuthorizationController),
    AccessTokenController: Object.assign(AccessTokenController, AccessTokenController),
    TransientTokenController: Object.assign(TransientTokenController, TransientTokenController),
}

export default Controllers