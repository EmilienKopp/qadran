import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ClockEntryController::store
* @see app/Http/Controllers/ClockEntryController.php:34
* @route '/clock-entry/store'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/clock-entry/store',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ClockEntryController::store
* @see app/Http/Controllers/ClockEntryController.php:34
* @route '/clock-entry/store'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClockEntryController::store
* @see app/Http/Controllers/ClockEntryController.php:34
* @route '/clock-entry/store'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ClockEntryController::index
* @see app/Http/Controllers/ClockEntryController.php:18
* @route '/clock-entries'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/clock-entries',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ClockEntryController::index
* @see app/Http/Controllers/ClockEntryController.php:18
* @route '/clock-entries'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClockEntryController::index
* @see app/Http/Controllers/ClockEntryController.php:18
* @route '/clock-entries'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ClockEntryController::index
* @see app/Http/Controllers/ClockEntryController.php:18
* @route '/clock-entries'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ClockEntryController::show
* @see app/Http/Controllers/ClockEntryController.php:69
* @route '/clock-entries/{clockEntry}'
*/
export const show = (args: { clockEntry: number | { id: number } } | [clockEntry: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/clock-entries/{clockEntry}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ClockEntryController::show
* @see app/Http/Controllers/ClockEntryController.php:69
* @route '/clock-entries/{clockEntry}'
*/
show.url = (args: { clockEntry: number | { id: number } } | [clockEntry: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { clockEntry: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { clockEntry: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            clockEntry: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        clockEntry: typeof args.clockEntry === 'object'
        ? args.clockEntry.id
        : args.clockEntry,
    }

    return show.definition.url
            .replace('{clockEntry}', parsedArgs.clockEntry.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClockEntryController::show
* @see app/Http/Controllers/ClockEntryController.php:69
* @route '/clock-entries/{clockEntry}'
*/
show.get = (args: { clockEntry: number | { id: number } } | [clockEntry: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ClockEntryController::show
* @see app/Http/Controllers/ClockEntryController.php:69
* @route '/clock-entries/{clockEntry}'
*/
show.head = (args: { clockEntry: number | { id: number } } | [clockEntry: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ClockEntryController::edit
* @see app/Http/Controllers/ClockEntryController.php:77
* @route '/clock-entries/{clockEntry}/edit'
*/
export const edit = (args: { clockEntry: number | { id: number } } | [clockEntry: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/clock-entries/{clockEntry}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ClockEntryController::edit
* @see app/Http/Controllers/ClockEntryController.php:77
* @route '/clock-entries/{clockEntry}/edit'
*/
edit.url = (args: { clockEntry: number | { id: number } } | [clockEntry: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { clockEntry: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { clockEntry: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            clockEntry: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        clockEntry: typeof args.clockEntry === 'object'
        ? args.clockEntry.id
        : args.clockEntry,
    }

    return edit.definition.url
            .replace('{clockEntry}', parsedArgs.clockEntry.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClockEntryController::edit
* @see app/Http/Controllers/ClockEntryController.php:77
* @route '/clock-entries/{clockEntry}/edit'
*/
edit.get = (args: { clockEntry: number | { id: number } } | [clockEntry: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ClockEntryController::edit
* @see app/Http/Controllers/ClockEntryController.php:77
* @route '/clock-entries/{clockEntry}/edit'
*/
edit.head = (args: { clockEntry: number | { id: number } } | [clockEntry: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ClockEntryController::update
* @see app/Http/Controllers/ClockEntryController.php:85
* @route '/clock-entries/{clockEntry}'
*/
export const update = (args: { clockEntry: number | { id: number } } | [clockEntry: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

update.definition = {
    methods: ["patch"],
    url: '/clock-entries/{clockEntry}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\ClockEntryController::update
* @see app/Http/Controllers/ClockEntryController.php:85
* @route '/clock-entries/{clockEntry}'
*/
update.url = (args: { clockEntry: number | { id: number } } | [clockEntry: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { clockEntry: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { clockEntry: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            clockEntry: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        clockEntry: typeof args.clockEntry === 'object'
        ? args.clockEntry.id
        : args.clockEntry,
    }

    return update.definition.url
            .replace('{clockEntry}', parsedArgs.clockEntry.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClockEntryController::update
* @see app/Http/Controllers/ClockEntryController.php:85
* @route '/clock-entries/{clockEntry}'
*/
update.patch = (args: { clockEntry: number | { id: number } } | [clockEntry: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\ClockEntryController::destroy
* @see app/Http/Controllers/ClockEntryController.php:93
* @route '/clock-entries/{clockEntry}'
*/
export const destroy = (args: { clockEntry: number | { id: number } } | [clockEntry: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/clock-entries/{clockEntry}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ClockEntryController::destroy
* @see app/Http/Controllers/ClockEntryController.php:93
* @route '/clock-entries/{clockEntry}'
*/
destroy.url = (args: { clockEntry: number | { id: number } } | [clockEntry: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { clockEntry: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { clockEntry: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            clockEntry: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        clockEntry: typeof args.clockEntry === 'object'
        ? args.clockEntry.id
        : args.clockEntry,
    }

    return destroy.definition.url
            .replace('{clockEntry}', parsedArgs.clockEntry.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClockEntryController::destroy
* @see app/Http/Controllers/ClockEntryController.php:93
* @route '/clock-entries/{clockEntry}'
*/
destroy.delete = (args: { clockEntry: number | { id: number } } | [clockEntry: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const ClockEntryController = { store, index, show, edit, update, destroy }

export default ClockEntryController