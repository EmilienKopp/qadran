import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ActivityController::index
* @see app/Http/Controllers/ActivityController.php:35
* @param account - Default: '$subdomain'
* @route '/{account?}/activities'
*/
export const index = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/{account?}/activities',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActivityController::index
* @see app/Http/Controllers/ActivityController.php:35
* @param account - Default: '$subdomain'
* @route '/{account?}/activities'
*/
index.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return index.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActivityController::index
* @see app/Http/Controllers/ActivityController.php:35
* @param account - Default: '$subdomain'
* @route '/{account?}/activities'
*/
index.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActivityController::index
* @see app/Http/Controllers/ActivityController.php:35
* @param account - Default: '$subdomain'
* @route '/{account?}/activities'
*/
index.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ActivityController::store
* @see app/Http/Controllers/ActivityController.php:65
* @param account - Default: '$subdomain'
* @route '/{account?}/activities/store'
*/
export const store = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/{account?}/activities/store',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ActivityController::store
* @see app/Http/Controllers/ActivityController.php:65
* @param account - Default: '$subdomain'
* @route '/{account?}/activities/store'
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
* @see \App\Http\Controllers\ActivityController::store
* @see app/Http/Controllers/ActivityController.php:65
* @param account - Default: '$subdomain'
* @route '/{account?}/activities/store'
*/
store.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ActivityController::show
* @see app/Http/Controllers/ActivityController.php:76
* @param account - Default: '$subdomain'
* @route '/{account?}/activities/{date}'
*/
export const show = (args: { account?: string | { id: string }, date: string | number } | [account: string | { id: string }, date: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/{account?}/activities/{date}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActivityController::show
* @see app/Http/Controllers/ActivityController.php:76
* @param account - Default: '$subdomain'
* @route '/{account?}/activities/{date}'
*/
show.url = (args: { account?: string | { id: string }, date: string | number } | [account: string | { id: string }, date: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            date: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: (typeof args.account === 'object'
        ? args.account.id
        : args.account) ?? '$subdomain',
        date: args.date,
    }

    return show.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{date}', parsedArgs.date.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActivityController::show
* @see app/Http/Controllers/ActivityController.php:76
* @param account - Default: '$subdomain'
* @route '/{account?}/activities/{date}'
*/
show.get = (args: { account?: string | { id: string }, date: string | number } | [account: string | { id: string }, date: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActivityController::show
* @see app/Http/Controllers/ActivityController.php:76
* @param account - Default: '$subdomain'
* @route '/{account?}/activities/{date}'
*/
show.head = (args: { account?: string | { id: string }, date: string | number } | [account: string | { id: string }, date: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ActivityController::deleteEntry
* @see app/Http/Controllers/ActivityController.php:142
* @param account - Default: '$subdomain'
* @route '/{account?}/activities/clock-entry/{clockEntry}'
*/
export const deleteEntry = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: deleteEntry.url(args, options),
    method: 'delete',
})

deleteEntry.definition = {
    methods: ["delete"],
    url: '/{account?}/activities/clock-entry/{clockEntry}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ActivityController::deleteEntry
* @see app/Http/Controllers/ActivityController.php:142
* @param account - Default: '$subdomain'
* @route '/{account?}/activities/clock-entry/{clockEntry}'
*/
deleteEntry.url = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            clockEntry: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        clockEntry: typeof args.clockEntry === 'object'
        ? args.clockEntry.id
        : args.clockEntry,
    }

    return deleteEntry.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{clockEntry}', parsedArgs.clockEntry.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActivityController::deleteEntry
* @see app/Http/Controllers/ActivityController.php:142
* @param account - Default: '$subdomain'
* @route '/{account?}/activities/clock-entry/{clockEntry}'
*/
deleteEntry.delete = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: deleteEntry.url(args, options),
    method: 'delete',
})

const ActivityController = { index, store, show, deleteEntry }

export default ActivityController