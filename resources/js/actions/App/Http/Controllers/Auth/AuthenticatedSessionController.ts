import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../../wayfinder'
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
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::storeSpaceSelection
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:46
* @route '/space-selection'
*/
export const storeSpaceSelection = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeSpaceSelection.url(options),
    method: 'post',
})

storeSpaceSelection.definition = {
    methods: ["post"],
    url: '/space-selection',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::storeSpaceSelection
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:46
* @route '/space-selection'
*/
storeSpaceSelection.url = (options?: RouteQueryOptions) => {
    return storeSpaceSelection.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::storeSpaceSelection
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:46
* @route '/space-selection'
*/
storeSpaceSelection.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeSpaceSelection.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::create
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:76
* @route '/login'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/login',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::create
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:76
* @route '/login'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::create
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:76
* @route '/login'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::create
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:76
* @route '/login'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
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
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::store
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:191
* @route '/login'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/login',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::store
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:191
* @route '/login'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::store
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:191
* @route '/login'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::destroy
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:226
* @param account - Default: '$subdomain'
* @route '/{account?}/logout'
*/
export const destroy = (args?: { account?: string | { id: string } } | [account: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: destroy.url(args, options),
    method: 'post',
})

destroy.definition = {
    methods: ["post"],
    url: '/{account?}/logout',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::destroy
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:226
* @param account - Default: '$subdomain'
* @route '/{account?}/logout'
*/
destroy.url = (args?: { account?: string | { id: string } } | [account: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::destroy
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:226
* @param account - Default: '$subdomain'
* @route '/{account?}/logout'
*/
destroy.post = (args?: { account?: string | { id: string } } | [account: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: destroy.url(args, options),
    method: 'post',
})

const AuthenticatedSessionController = { spaceSelection, storeSpaceSelection, create, authenticate, store, destroy }

export default AuthenticatedSessionController