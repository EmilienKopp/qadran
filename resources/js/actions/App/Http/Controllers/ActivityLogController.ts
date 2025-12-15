import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ActivityLogController::store
* @see app/Http/Controllers/ActivityLogController.php:30
* @param account - Default: '$subdomain'
* @route '/{account?}/activity-logs/store'
*/
export const store = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/{account?}/activity-logs/store',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActivityLogController::store
* @see app/Http/Controllers/ActivityLogController.php:30
* @param account - Default: '$subdomain'
* @route '/{account?}/activity-logs/store'
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
* @see \App\Http\Controllers\ActivityLogController::store
* @see app/Http/Controllers/ActivityLogController.php:30
* @param account - Default: '$subdomain'
* @route '/{account?}/activity-logs/store'
*/
store.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

const ActivityLogController = { store }

export default ActivityLogController