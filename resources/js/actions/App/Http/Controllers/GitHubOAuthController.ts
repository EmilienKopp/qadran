import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\GitHubOAuthController::redirect
* @see app/Http/Controllers/GitHubOAuthController.php:30
* @route '/github/login'
*/
const redirect2c4ab929dd705c98bef30d08308c9646 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: redirect2c4ab929dd705c98bef30d08308c9646.url(options),
    method: 'get',
})

redirect2c4ab929dd705c98bef30d08308c9646.definition = {
    methods: ["get","head"],
    url: '/github/login',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\GitHubOAuthController::redirect
* @see app/Http/Controllers/GitHubOAuthController.php:30
* @route '/github/login'
*/
redirect2c4ab929dd705c98bef30d08308c9646.url = (options?: RouteQueryOptions) => {
    return redirect2c4ab929dd705c98bef30d08308c9646.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\GitHubOAuthController::redirect
* @see app/Http/Controllers/GitHubOAuthController.php:30
* @route '/github/login'
*/
redirect2c4ab929dd705c98bef30d08308c9646.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: redirect2c4ab929dd705c98bef30d08308c9646.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GitHubOAuthController::redirect
* @see app/Http/Controllers/GitHubOAuthController.php:30
* @route '/github/login'
*/
redirect2c4ab929dd705c98bef30d08308c9646.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: redirect2c4ab929dd705c98bef30d08308c9646.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\GitHubOAuthController::redirect
* @see app/Http/Controllers/GitHubOAuthController.php:30
* @param account - Default: '$subdomain'
* @route '/{account?}/settings/github/connect'
*/
const redirectb81f78622e987f3da4c489de71f65383 = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: redirectb81f78622e987f3da4c489de71f65383.url(args, options),
    method: 'get',
})

redirectb81f78622e987f3da4c489de71f65383.definition = {
    methods: ["get","head"],
    url: '/{account?}/settings/github/connect',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\GitHubOAuthController::redirect
* @see app/Http/Controllers/GitHubOAuthController.php:30
* @param account - Default: '$subdomain'
* @route '/{account?}/settings/github/connect'
*/
redirectb81f78622e987f3da4c489de71f65383.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return redirectb81f78622e987f3da4c489de71f65383.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\GitHubOAuthController::redirect
* @see app/Http/Controllers/GitHubOAuthController.php:30
* @param account - Default: '$subdomain'
* @route '/{account?}/settings/github/connect'
*/
redirectb81f78622e987f3da4c489de71f65383.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: redirectb81f78622e987f3da4c489de71f65383.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GitHubOAuthController::redirect
* @see app/Http/Controllers/GitHubOAuthController.php:30
* @param account - Default: '$subdomain'
* @route '/{account?}/settings/github/connect'
*/
redirectb81f78622e987f3da4c489de71f65383.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: redirectb81f78622e987f3da4c489de71f65383.url(args, options),
    method: 'head',
})

export const redirect = {
    '/github/login': redirect2c4ab929dd705c98bef30d08308c9646,
    '/{account?}/settings/github/connect': redirectb81f78622e987f3da4c489de71f65383,
}

/**
* @see \App\Http\Controllers\GitHubOAuthController::callback
* @see app/Http/Controllers/GitHubOAuthController.php:51
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
* @see app/Http/Controllers/GitHubOAuthController.php:51
* @route '/auth/github/callback'
*/
callback.url = (options?: RouteQueryOptions) => {
    return callback.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\GitHubOAuthController::callback
* @see app/Http/Controllers/GitHubOAuthController.php:51
* @route '/auth/github/callback'
*/
callback.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: callback.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GitHubOAuthController::callback
* @see app/Http/Controllers/GitHubOAuthController.php:51
* @route '/auth/github/callback'
*/
callback.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: callback.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\GitHubOAuthController::confirmReplace
* @see app/Http/Controllers/GitHubOAuthController.php:243
* @param account - Default: '$subdomain'
* @route '/{account?}/settings/github/confirm-replace'
*/
export const confirmReplace = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: confirmReplace.url(args, options),
    method: 'post',
})

confirmReplace.definition = {
    methods: ["post"],
    url: '/{account?}/settings/github/confirm-replace',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\GitHubOAuthController::confirmReplace
* @see app/Http/Controllers/GitHubOAuthController.php:243
* @param account - Default: '$subdomain'
* @route '/{account?}/settings/github/confirm-replace'
*/
confirmReplace.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return confirmReplace.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\GitHubOAuthController::confirmReplace
* @see app/Http/Controllers/GitHubOAuthController.php:243
* @param account - Default: '$subdomain'
* @route '/{account?}/settings/github/confirm-replace'
*/
confirmReplace.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: confirmReplace.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\GitHubOAuthController::disconnect
* @see app/Http/Controllers/GitHubOAuthController.php:294
* @param account - Default: '$subdomain'
* @route '/{account?}/settings/github/disconnect'
*/
export const disconnect = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: disconnect.url(args, options),
    method: 'delete',
})

disconnect.definition = {
    methods: ["delete"],
    url: '/{account?}/settings/github/disconnect',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\GitHubOAuthController::disconnect
* @see app/Http/Controllers/GitHubOAuthController.php:294
* @param account - Default: '$subdomain'
* @route '/{account?}/settings/github/disconnect'
*/
disconnect.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return disconnect.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\GitHubOAuthController::disconnect
* @see app/Http/Controllers/GitHubOAuthController.php:294
* @param account - Default: '$subdomain'
* @route '/{account?}/settings/github/disconnect'
*/
disconnect.delete = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: disconnect.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\GitHubOAuthController::status
* @see app/Http/Controllers/GitHubOAuthController.php:313
* @param account - Default: '$subdomain'
* @route '/{account?}/api/github/status'
*/
export const status = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: status.url(args, options),
    method: 'get',
})

status.definition = {
    methods: ["get","head"],
    url: '/{account?}/api/github/status',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\GitHubOAuthController::status
* @see app/Http/Controllers/GitHubOAuthController.php:313
* @param account - Default: '$subdomain'
* @route '/{account?}/api/github/status'
*/
status.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return status.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\GitHubOAuthController::status
* @see app/Http/Controllers/GitHubOAuthController.php:313
* @param account - Default: '$subdomain'
* @route '/{account?}/api/github/status'
*/
status.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: status.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GitHubOAuthController::status
* @see app/Http/Controllers/GitHubOAuthController.php:313
* @param account - Default: '$subdomain'
* @route '/{account?}/api/github/status'
*/
status.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: status.url(args, options),
    method: 'head',
})

const GitHubOAuthController = { redirect, callback, confirmReplace, disconnect, status }

export default GitHubOAuthController