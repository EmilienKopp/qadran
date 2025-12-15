import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../wayfinder'
/**
* @see routes/web.php:24
* @route '/'
*/
export const root = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: root.url(options),
    method: 'get',
})

root.definition = {
    methods: ["get","head"],
    url: '/',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/web.php:24
* @route '/'
*/
root.url = (options?: RouteQueryOptions) => {
    return root.definition.url + queryParams(options)
}

/**
* @see routes/web.php:24
* @route '/'
*/
root.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: root.url(options),
    method: 'get',
})

/**
* @see routes/web.php:24
* @route '/'
*/
root.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: root.url(options),
    method: 'head',
})

/**
* @see routes/web.php:31
* @route '/find-tenant'
*/
export const findTenant = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: findTenant.url(options),
    method: 'post',
})

findTenant.definition = {
    methods: ["post"],
    url: '/find-tenant',
} satisfies RouteDefinition<["post"]>

/**
* @see routes/web.php:31
* @route '/find-tenant'
*/
findTenant.url = (options?: RouteQueryOptions) => {
    return findTenant.definition.url + queryParams(options)
}

/**
* @see routes/web.php:31
* @route '/find-tenant'
*/
findTenant.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: findTenant.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::spaceSelection
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:33
* @route '/space-selection'
*/
export const spaceSelection = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: spaceSelection.url(options),
    method: 'get',
})

spaceSelection.definition = {
    methods: ["get","head"],
    url: '/space-selection',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::spaceSelection
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:33
* @route '/space-selection'
*/
spaceSelection.url = (options?: RouteQueryOptions) => {
    return spaceSelection.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::spaceSelection
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:33
* @route '/space-selection'
*/
spaceSelection.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: spaceSelection.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::spaceSelection
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:33
* @route '/space-selection'
*/
spaceSelection.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: spaceSelection.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\RegisteredUserController::register
* @see app/Http/Controllers/Auth/RegisteredUserController.php:28
* @route '/welcome/register'
*/
export const register = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})

register.definition = {
    methods: ["get","head"],
    url: '/welcome/register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\RegisteredUserController::register
* @see app/Http/Controllers/Auth/RegisteredUserController.php:28
* @route '/welcome/register'
*/
register.url = (options?: RouteQueryOptions) => {
    return register.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\RegisteredUserController::register
* @see app/Http/Controllers/Auth/RegisteredUserController.php:28
* @route '/welcome/register'
*/
register.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: register.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\RegisteredUserController::register
* @see app/Http/Controllers/Auth/RegisteredUserController.php:28
* @route '/welcome/register'
*/
register.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: register.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::loginPage
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:76
* @route '/login'
*/
export const loginPage = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: loginPage.url(options),
    method: 'get',
})

loginPage.definition = {
    methods: ["get","head"],
    url: '/login',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::loginPage
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:76
* @route '/login'
*/
loginPage.url = (options?: RouteQueryOptions) => {
    return loginPage.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::loginPage
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:76
* @route '/login'
*/
loginPage.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: loginPage.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::loginPage
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:76
* @route '/login'
*/
loginPage.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: loginPage.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::authenticate
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:97
* @route '/authenticate'
*/
export const authenticate = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: authenticate.url(options),
    method: 'get',
})

authenticate.definition = {
    methods: ["get","head"],
    url: '/authenticate',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::authenticate
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:97
* @route '/authenticate'
*/
authenticate.url = (options?: RouteQueryOptions) => {
    return authenticate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::authenticate
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:97
* @route '/authenticate'
*/
authenticate.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: authenticate.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::authenticate
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:97
* @route '/authenticate'
*/
authenticate.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: authenticate.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::login
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:191
* @route '/login'
*/
export const login = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: login.url(options),
    method: 'post',
})

login.definition = {
    methods: ["post"],
    url: '/login',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::login
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:191
* @route '/login'
*/
login.url = (options?: RouteQueryOptions) => {
    return login.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::login
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:191
* @route '/login'
*/
login.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: login.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::logout
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:226
* @param account - Default: '$subdomain'
* @route '/{account?}/logout'
*/
export const logout = (args?: { account?: string | { id: string } } | [account: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(args, options),
    method: 'post',
})

logout.definition = {
    methods: ["post"],
    url: '/{account?}/logout',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::logout
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:226
* @param account - Default: '$subdomain'
* @route '/{account?}/logout'
*/
logout.url = (args?: { account?: string | { id: string } } | [account: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { account: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { account: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            account: args[0],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: (typeof args?.account === 'object'
        ? args.account.id
        : args?.account) ?? '$subdomain',
    }

    return logout.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::logout
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:226
* @param account - Default: '$subdomain'
* @route '/{account?}/logout'
*/
logout.post = (args?: { account?: string | { id: string } } | [account: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: logout.url(args, options),
    method: 'post',
})

/**
* @see routes/tenant.php:83
* @param account - Default: '$subdomain'
* @route '/{account?}/dashboard'
*/
export const dashboard = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(args, options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/{account?}/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/tenant.php:83
* @param account - Default: '$subdomain'
* @route '/{account?}/dashboard'
*/
dashboard.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { account: args }
    }

    if (Array.isArray(args)) {
        args = {
            account: args[0],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args?.account ?? '$subdomain',
    }

    return dashboard.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see routes/tenant.php:83
* @param account - Default: '$subdomain'
* @route '/{account?}/dashboard'
*/
dashboard.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(args, options),
    method: 'get',
})

/**
* @see routes/tenant.php:83
* @param account - Default: '$subdomain'
* @route '/{account?}/dashboard'
*/
dashboard.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(args, options),
    method: 'head',
})

/**
* @see routes/tenant.php:90
* @param account - Default: '$subdomain'
* @route '/{account?}/terminal'
*/
export const terminal = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: terminal.url(args, options),
    method: 'get',
})

terminal.definition = {
    methods: ["get","head"],
    url: '/{account?}/terminal',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/tenant.php:90
* @param account - Default: '$subdomain'
* @route '/{account?}/terminal'
*/
terminal.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { account: args }
    }

    if (Array.isArray(args)) {
        args = {
            account: args[0],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args?.account ?? '$subdomain',
    }

    return terminal.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see routes/tenant.php:90
* @param account - Default: '$subdomain'
* @route '/{account?}/terminal'
*/
terminal.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: terminal.url(args, options),
    method: 'get',
})

/**
* @see routes/tenant.php:90
* @param account - Default: '$subdomain'
* @route '/{account?}/terminal'
*/
terminal.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: terminal.url(args, options),
    method: 'head',
})

