import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\GitHubOAuthController::connect
* @see app/Http/Controllers/GitHubOAuthController.php:18
* @route '/settings/github/connect'
*/
export const connect = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: connect.url(options),
    method: 'get',
})

connect.definition = {
    methods: ["get","head"],
    url: '/settings/github/connect',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\GitHubOAuthController::connect
* @see app/Http/Controllers/GitHubOAuthController.php:18
* @route '/settings/github/connect'
*/
connect.url = (options?: RouteQueryOptions) => {
    return connect.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\GitHubOAuthController::connect
* @see app/Http/Controllers/GitHubOAuthController.php:18
* @route '/settings/github/connect'
*/
connect.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: connect.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GitHubOAuthController::connect
* @see app/Http/Controllers/GitHubOAuthController.php:18
* @route '/settings/github/connect'
*/
connect.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: connect.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\GitHubOAuthController::callback
* @see app/Http/Controllers/GitHubOAuthController.php:34
* @route '/auth/github/callback'
*/
export const callback = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: callback.url(options),
    method: 'get',
})

callback.definition = {
    methods: ["get","head"],
    url: '/auth/github/callback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\GitHubOAuthController::callback
* @see app/Http/Controllers/GitHubOAuthController.php:34
* @route '/auth/github/callback'
*/
callback.url = (options?: RouteQueryOptions) => {
    return callback.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\GitHubOAuthController::callback
* @see app/Http/Controllers/GitHubOAuthController.php:34
* @route '/auth/github/callback'
*/
callback.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: callback.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GitHubOAuthController::callback
* @see app/Http/Controllers/GitHubOAuthController.php:34
* @route '/auth/github/callback'
*/
callback.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: callback.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\GitHubOAuthController::confirmReplace
* @see app/Http/Controllers/GitHubOAuthController.php:107
* @route '/settings/github/confirm-replace'
*/
export const confirmReplace = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: confirmReplace.url(options),
    method: 'post',
})

confirmReplace.definition = {
    methods: ["post"],
    url: '/settings/github/confirm-replace',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\GitHubOAuthController::confirmReplace
* @see app/Http/Controllers/GitHubOAuthController.php:107
* @route '/settings/github/confirm-replace'
*/
confirmReplace.url = (options?: RouteQueryOptions) => {
    return confirmReplace.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\GitHubOAuthController::confirmReplace
* @see app/Http/Controllers/GitHubOAuthController.php:107
* @route '/settings/github/confirm-replace'
*/
confirmReplace.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: confirmReplace.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\GitHubOAuthController::disconnect
* @see app/Http/Controllers/GitHubOAuthController.php:157
* @route '/settings/github/disconnect'
*/
export const disconnect = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: disconnect.url(options),
    method: 'delete',
})

disconnect.definition = {
    methods: ["delete"],
    url: '/settings/github/disconnect',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\GitHubOAuthController::disconnect
* @see app/Http/Controllers/GitHubOAuthController.php:157
* @route '/settings/github/disconnect'
*/
disconnect.url = (options?: RouteQueryOptions) => {
    return disconnect.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\GitHubOAuthController::disconnect
* @see app/Http/Controllers/GitHubOAuthController.php:157
* @route '/settings/github/disconnect'
*/
disconnect.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: disconnect.url(options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\GitHubOAuthController::status
* @see app/Http/Controllers/GitHubOAuthController.php:176
* @route '/api/github/status'
*/
export const status = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: status.url(options),
    method: 'get',
})

status.definition = {
    methods: ["get","head"],
    url: '/api/github/status',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\GitHubOAuthController::status
* @see app/Http/Controllers/GitHubOAuthController.php:176
* @route '/api/github/status'
*/
status.url = (options?: RouteQueryOptions) => {
    return status.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\GitHubOAuthController::status
* @see app/Http/Controllers/GitHubOAuthController.php:176
* @route '/api/github/status'
*/
status.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: status.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GitHubOAuthController::status
* @see app/Http/Controllers/GitHubOAuthController.php:176
* @route '/api/github/status'
*/
status.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: status.url(options),
    method: 'head',
})

const github = {
    connect: Object.assign(connect, connect),
    callback: Object.assign(callback, callback),
    confirmReplace: Object.assign(confirmReplace, confirmReplace),
    disconnect: Object.assign(disconnect, disconnect),
    status: Object.assign(status, status),
}

export default github