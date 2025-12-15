import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\ClockEntryController::index
* @see app/Http/Controllers/Api/ClockEntryController.php:15
* @route '/api/clock-entries'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/clock-entries',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ClockEntryController::index
* @see app/Http/Controllers/Api/ClockEntryController.php:15
* @route '/api/clock-entries'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ClockEntryController::index
* @see app/Http/Controllers/Api/ClockEntryController.php:15
* @route '/api/clock-entries'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ClockEntryController::index
* @see app/Http/Controllers/Api/ClockEntryController.php:15
* @route '/api/clock-entries'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\ClockEntryController::show
* @see app/Http/Controllers/Api/ClockEntryController.php:20
* @route '/api/clock-entries/{id}'
*/
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/api/clock-entries/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ClockEntryController::show
* @see app/Http/Controllers/Api/ClockEntryController.php:20
* @route '/api/clock-entries/{id}'
*/
show.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    if (Array.isArray(args)) {
        args = {
            id: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        id: args.id,
    }

    return show.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ClockEntryController::show
* @see app/Http/Controllers/Api/ClockEntryController.php:20
* @route '/api/clock-entries/{id}'
*/
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ClockEntryController::show
* @see app/Http/Controllers/Api/ClockEntryController.php:20
* @route '/api/clock-entries/{id}'
*/
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\ClockEntryController::byUser
* @see app/Http/Controllers/Api/ClockEntryController.php:27
* @route '/api/clock-entries/by-user/{userId}'
*/
export const byUser = (args: { userId: string | number } | [userId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byUser.url(args, options),
    method: 'get',
})

byUser.definition = {
    methods: ["get","head"],
    url: '/api/clock-entries/by-user/{userId}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ClockEntryController::byUser
* @see app/Http/Controllers/Api/ClockEntryController.php:27
* @route '/api/clock-entries/by-user/{userId}'
*/
byUser.url = (args: { userId: string | number } | [userId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { userId: args }
    }

    if (Array.isArray(args)) {
        args = {
            userId: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        userId: args.userId,
    }

    return byUser.definition.url
            .replace('{userId}', parsedArgs.userId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ClockEntryController::byUser
* @see app/Http/Controllers/Api/ClockEntryController.php:27
* @route '/api/clock-entries/by-user/{userId}'
*/
byUser.get = (args: { userId: string | number } | [userId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byUser.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ClockEntryController::byUser
* @see app/Http/Controllers/Api/ClockEntryController.php:27
* @route '/api/clock-entries/by-user/{userId}'
*/
byUser.head = (args: { userId: string | number } | [userId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: byUser.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\ClockEntryController::activeByUser
* @see app/Http/Controllers/Api/ClockEntryController.php:34
* @route '/api/clock-entries/active-by-user/{userId}'
*/
export const activeByUser = (args: { userId: string | number } | [userId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: activeByUser.url(args, options),
    method: 'get',
})

activeByUser.definition = {
    methods: ["get","head"],
    url: '/api/clock-entries/active-by-user/{userId}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ClockEntryController::activeByUser
* @see app/Http/Controllers/Api/ClockEntryController.php:34
* @route '/api/clock-entries/active-by-user/{userId}'
*/
activeByUser.url = (args: { userId: string | number } | [userId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { userId: args }
    }

    if (Array.isArray(args)) {
        args = {
            userId: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        userId: args.userId,
    }

    return activeByUser.definition.url
            .replace('{userId}', parsedArgs.userId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ClockEntryController::activeByUser
* @see app/Http/Controllers/Api/ClockEntryController.php:34
* @route '/api/clock-entries/active-by-user/{userId}'
*/
activeByUser.get = (args: { userId: string | number } | [userId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: activeByUser.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ClockEntryController::activeByUser
* @see app/Http/Controllers/Api/ClockEntryController.php:34
* @route '/api/clock-entries/active-by-user/{userId}'
*/
activeByUser.head = (args: { userId: string | number } | [userId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: activeByUser.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\ClockEntryController::store
* @see app/Http/Controllers/Api/ClockEntryController.php:41
* @route '/api/clock-entries'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/clock-entries',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\ClockEntryController::store
* @see app/Http/Controllers/Api/ClockEntryController.php:41
* @route '/api/clock-entries'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ClockEntryController::store
* @see app/Http/Controllers/Api/ClockEntryController.php:41
* @route '/api/clock-entries'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\ClockEntryController::update
* @see app/Http/Controllers/Api/ClockEntryController.php:54
* @route '/api/clock-entries/{id}'
*/
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/api/clock-entries/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Api\ClockEntryController::update
* @see app/Http/Controllers/Api/ClockEntryController.php:54
* @route '/api/clock-entries/{id}'
*/
update.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    if (Array.isArray(args)) {
        args = {
            id: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        id: args.id,
    }

    return update.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ClockEntryController::update
* @see app/Http/Controllers/Api/ClockEntryController.php:54
* @route '/api/clock-entries/{id}'
*/
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Api\ClockEntryController::destroy
* @see app/Http/Controllers/Api/ClockEntryController.php:69
* @route '/api/clock-entries/{id}'
*/
export const destroy = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/api/clock-entries/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Api\ClockEntryController::destroy
* @see app/Http/Controllers/Api/ClockEntryController.php:69
* @route '/api/clock-entries/{id}'
*/
destroy.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    if (Array.isArray(args)) {
        args = {
            id: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        id: args.id,
    }

    return destroy.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ClockEntryController::destroy
* @see app/Http/Controllers/Api/ClockEntryController.php:69
* @route '/api/clock-entries/{id}'
*/
destroy.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const ClockEntryController = { index, show, byUser, activeByUser, store, update, destroy }

export default ClockEntryController