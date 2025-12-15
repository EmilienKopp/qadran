import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../wayfinder'
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
export const destroy = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/{account?}/clock-entries/{clockEntry}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ClockEntryController::destroy
* @see app/Http/Controllers/ClockEntryController.php:114
* @param account - Default: '$subdomain'
* @route '/{account?}/clock-entries/{clockEntry}'
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
* @route '/{account?}/clock-entries/{clockEntry}'
*/
destroy.delete = (args: { account?: string | number, clockEntry: string | number | { id: string | number } } | [account: string | number, clockEntry: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const clockEntry = {
    store: Object.assign(store, store),
    index: Object.assign(index, index),
    show: Object.assign(show, show),
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default clockEntry