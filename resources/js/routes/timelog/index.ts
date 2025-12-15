import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../wayfinder'
/**
* @see \App\Http\Controllers\ClockEntryController::batchUpdate
* @see app/Http/Controllers/ClockEntryController.php:89
* @param account - Default: '$subdomain'
* @route '/{account?}/timelog/batch-update'
*/
export const batchUpdate = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: batchUpdate.url(args, options),
    method: 'put',
})

batchUpdate.definition = {
    methods: ["put"],
    url: '/{account?}/timelog/batch-update',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\ClockEntryController::batchUpdate
* @see app/Http/Controllers/ClockEntryController.php:89
* @param account - Default: '$subdomain'
* @route '/{account?}/timelog/batch-update'
*/
batchUpdate.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return batchUpdate.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClockEntryController::batchUpdate
* @see app/Http/Controllers/ClockEntryController.php:89
* @param account - Default: '$subdomain'
* @route '/{account?}/timelog/batch-update'
*/
batchUpdate.put = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: batchUpdate.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\ClockEntryController::destroy
* @see app/Http/Controllers/ClockEntryController.php:114
* @param account - Default: '$subdomain'
* @route '/{account?}/timelog/{clockEntry}'
*/
export const destroy = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/{account?}/timelog/{clockEntry}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ClockEntryController::destroy
* @see app/Http/Controllers/ClockEntryController.php:114
* @param account - Default: '$subdomain'
* @route '/{account?}/timelog/{clockEntry}'
*/
destroy.url = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{clockEntry}', parsedArgs.clockEntry.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClockEntryController::destroy
* @see app/Http/Controllers/ClockEntryController.php:114
* @param account - Default: '$subdomain'
* @route '/{account?}/timelog/{clockEntry}'
*/
destroy.delete = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const timelog = {
    batchUpdate: Object.assign(batchUpdate, batchUpdate),
    destroy: Object.assign(destroy, destroy),
}

export default timelog