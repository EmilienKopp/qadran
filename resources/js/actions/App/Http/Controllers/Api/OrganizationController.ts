import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\OrganizationController::index
* @see app/Http/Controllers/Api/OrganizationController.php:15
* @route '/api/organizations'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/organizations',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\OrganizationController::index
* @see app/Http/Controllers/Api/OrganizationController.php:15
* @route '/api/organizations'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\OrganizationController::index
* @see app/Http/Controllers/Api/OrganizationController.php:15
* @route '/api/organizations'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\OrganizationController::index
* @see app/Http/Controllers/Api/OrganizationController.php:15
* @route '/api/organizations'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\OrganizationController::show
* @see app/Http/Controllers/Api/OrganizationController.php:20
* @route '/api/organizations/{id}'
*/
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/api/organizations/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\OrganizationController::show
* @see app/Http/Controllers/Api/OrganizationController.php:20
* @route '/api/organizations/{id}'
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
* @see \App\Http\Controllers\Api\OrganizationController::show
* @see app/Http/Controllers/Api/OrganizationController.php:20
* @route '/api/organizations/{id}'
*/
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\OrganizationController::show
* @see app/Http/Controllers/Api/OrganizationController.php:20
* @route '/api/organizations/{id}'
*/
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\OrganizationController::byUser
* @see app/Http/Controllers/Api/OrganizationController.php:27
* @route '/api/organizations/by-user/{userId}'
*/
export const byUser = (args: { userId: string | number } | [userId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byUser.url(args, options),
    method: 'get',
})

byUser.definition = {
    methods: ["get","head"],
    url: '/api/organizations/by-user/{userId}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\OrganizationController::byUser
* @see app/Http/Controllers/Api/OrganizationController.php:27
* @route '/api/organizations/by-user/{userId}'
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
* @see \App\Http\Controllers\Api\OrganizationController::byUser
* @see app/Http/Controllers/Api/OrganizationController.php:27
* @route '/api/organizations/by-user/{userId}'
*/
byUser.get = (args: { userId: string | number } | [userId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byUser.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\OrganizationController::byUser
* @see app/Http/Controllers/Api/OrganizationController.php:27
* @route '/api/organizations/by-user/{userId}'
*/
byUser.head = (args: { userId: string | number } | [userId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: byUser.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\OrganizationController::store
* @see app/Http/Controllers/Api/OrganizationController.php:34
* @route '/api/organizations'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/organizations',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\OrganizationController::store
* @see app/Http/Controllers/Api/OrganizationController.php:34
* @route '/api/organizations'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\OrganizationController::store
* @see app/Http/Controllers/Api/OrganizationController.php:34
* @route '/api/organizations'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\OrganizationController::update
* @see app/Http/Controllers/Api/OrganizationController.php:45
* @route '/api/organizations/{id}'
*/
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/api/organizations/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Api\OrganizationController::update
* @see app/Http/Controllers/Api/OrganizationController.php:45
* @route '/api/organizations/{id}'
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
* @see \App\Http\Controllers\Api\OrganizationController::update
* @see app/Http/Controllers/Api/OrganizationController.php:45
* @route '/api/organizations/{id}'
*/
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Api\OrganizationController::destroy
* @see app/Http/Controllers/Api/OrganizationController.php:59
* @route '/api/organizations/{id}'
*/
export const destroy = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/api/organizations/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Api\OrganizationController::destroy
* @see app/Http/Controllers/Api/OrganizationController.php:59
* @route '/api/organizations/{id}'
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
* @see \App\Http\Controllers\Api\OrganizationController::destroy
* @see app/Http/Controllers/Api/OrganizationController.php:59
* @route '/api/organizations/{id}'
*/
destroy.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const OrganizationController = { index, show, byUser, store, update, destroy }

export default OrganizationController