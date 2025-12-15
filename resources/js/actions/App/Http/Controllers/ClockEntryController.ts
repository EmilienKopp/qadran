import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ClockEntryController::store
* @see app/Http/Controllers/ClockEntryController.php:33
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entry/store'
*/
export const store = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/{account?}/clock-entry/store',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ClockEntryController::store
* @see app/Http/Controllers/ClockEntryController.php:33
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entry/store'
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
* @see \App\Http\Controllers\ClockEntryController::store
* @see app/Http/Controllers/ClockEntryController.php:33
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entry/store'
*/
store.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ClockEntryController::index
* @see app/Http/Controllers/ClockEntryController.php:17
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries'
*/
export const index = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/{account?}/clock-entries',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ClockEntryController::index
* @see app/Http/Controllers/ClockEntryController.php:17
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries'
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
* @see \App\Http\Controllers\ClockEntryController::index
* @see app/Http/Controllers/ClockEntryController.php:17
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries'
*/
index.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ClockEntryController::index
* @see app/Http/Controllers/ClockEntryController.php:17
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries'
*/
index.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ClockEntryController::show
* @see app/Http/Controllers/ClockEntryController.php:62
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries/{clockEntry}'
*/
export const show = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/{account?}/clock-entries/{clockEntry}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ClockEntryController::show
* @see app/Http/Controllers/ClockEntryController.php:62
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries/{clockEntry}'
*/
show.url = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
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

    return show.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{clockEntry}', parsedArgs.clockEntry.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClockEntryController::show
* @see app/Http/Controllers/ClockEntryController.php:62
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries/{clockEntry}'
*/
show.get = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ClockEntryController::show
* @see app/Http/Controllers/ClockEntryController.php:62
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries/{clockEntry}'
*/
show.head = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ClockEntryController::edit
* @see app/Http/Controllers/ClockEntryController.php:70
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries/{clockEntry}/edit'
*/
export const edit = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/{account?}/clock-entries/{clockEntry}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ClockEntryController::edit
* @see app/Http/Controllers/ClockEntryController.php:70
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries/{clockEntry}/edit'
*/
edit.url = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{clockEntry}', parsedArgs.clockEntry.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClockEntryController::edit
* @see app/Http/Controllers/ClockEntryController.php:70
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries/{clockEntry}/edit'
*/
edit.get = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ClockEntryController::edit
* @see app/Http/Controllers/ClockEntryController.php:70
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries/{clockEntry}/edit'
*/
edit.head = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ClockEntryController::update
* @see app/Http/Controllers/ClockEntryController.php:78
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries/{clockEntry}'
*/
export const update = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

update.definition = {
    methods: ["patch"],
    url: '/{account?}/clock-entries/{clockEntry}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\ClockEntryController::update
* @see app/Http/Controllers/ClockEntryController.php:78
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries/{clockEntry}'
*/
update.url = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{clockEntry}', parsedArgs.clockEntry.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClockEntryController::update
* @see app/Http/Controllers/ClockEntryController.php:78
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries/{clockEntry}'
*/
update.patch = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\ClockEntryController::destroy
* @see app/Http/Controllers/ClockEntryController.php:114
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries/{clockEntry}'
*/
const destroy347aeadbfc9a15e48d7524426eddaa7b = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy347aeadbfc9a15e48d7524426eddaa7b.url(args, options),
    method: 'delete',
})

destroy347aeadbfc9a15e48d7524426eddaa7b.definition = {
    methods: ["delete"],
    url: '/{account?}/clock-entries/{clockEntry}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ClockEntryController::destroy
* @see app/Http/Controllers/ClockEntryController.php:114
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries/{clockEntry}'
*/
destroy347aeadbfc9a15e48d7524426eddaa7b.url = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
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

    return destroy347aeadbfc9a15e48d7524426eddaa7b.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{clockEntry}', parsedArgs.clockEntry.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClockEntryController::destroy
* @see app/Http/Controllers/ClockEntryController.php:114
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries/{clockEntry}'
*/
destroy347aeadbfc9a15e48d7524426eddaa7b.delete = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy347aeadbfc9a15e48d7524426eddaa7b.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\ClockEntryController::destroy
* @see app/Http/Controllers/ClockEntryController.php:114
* @param account - Default: '$subdomain'
* @route '/{account?}/timelog/{clockEntry}'
*/
const destroyfc1e036f6823d923c2077ca6ae5f1afa = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyfc1e036f6823d923c2077ca6ae5f1afa.url(args, options),
    method: 'delete',
})

destroyfc1e036f6823d923c2077ca6ae5f1afa.definition = {
    methods: ["delete"],
    url: '/{account?}/timelog/{clockEntry}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ClockEntryController::destroy
* @see app/Http/Controllers/ClockEntryController.php:114
* @param account - Default: '$subdomain'
* @route '/{account?}/timelog/{clockEntry}'
*/
destroyfc1e036f6823d923c2077ca6ae5f1afa.url = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
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

    return destroyfc1e036f6823d923c2077ca6ae5f1afa.definition.url
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
destroyfc1e036f6823d923c2077ca6ae5f1afa.delete = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyfc1e036f6823d923c2077ca6ae5f1afa.url(args, options),
    method: 'delete',
})

export const destroy = {
    '/{account?}/clock-entries/{clockEntry}': destroy347aeadbfc9a15e48d7524426eddaa7b,
    '/{account?}/timelog/{clockEntry}': destroyfc1e036f6823d923c2077ca6ae5f1afa,
}

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

const ClockEntryController = { store, index, show, edit, update, destroy, batchUpdate }

export default ClockEntryController