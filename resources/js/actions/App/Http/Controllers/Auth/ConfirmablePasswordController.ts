import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\ConfirmablePasswordController::show
* @see app/Http/Controllers/Auth/ConfirmablePasswordController.php:18
* @param account - Default: '$subdomain'
* @route '/{account?}/confirm-password'
*/
export const show = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/{account?}/confirm-password',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\ConfirmablePasswordController::show
* @see app/Http/Controllers/Auth/ConfirmablePasswordController.php:18
* @param account - Default: '$subdomain'
* @route '/{account?}/confirm-password'
*/
show.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return show.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ConfirmablePasswordController::show
* @see app/Http/Controllers/Auth/ConfirmablePasswordController.php:18
* @param account - Default: '$subdomain'
* @route '/{account?}/confirm-password'
*/
show.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\ConfirmablePasswordController::show
* @see app/Http/Controllers/Auth/ConfirmablePasswordController.php:18
* @param account - Default: '$subdomain'
* @route '/{account?}/confirm-password'
*/
show.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\ConfirmablePasswordController::store
* @see app/Http/Controllers/Auth/ConfirmablePasswordController.php:26
* @param account - Default: '$subdomain'
* @route '/{account?}/confirm-password'
*/
export const store = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/{account?}/confirm-password',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\ConfirmablePasswordController::store
* @see app/Http/Controllers/Auth/ConfirmablePasswordController.php:26
* @param account - Default: '$subdomain'
* @route '/{account?}/confirm-password'
*/
store.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return store.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ConfirmablePasswordController::store
* @see app/Http/Controllers/Auth/ConfirmablePasswordController.php:26
* @param account - Default: '$subdomain'
* @route '/{account?}/confirm-password'
*/
store.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

const ConfirmablePasswordController = { show, store }

export default ConfirmablePasswordController