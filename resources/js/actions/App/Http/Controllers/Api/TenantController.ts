import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\TenantController::index
* @see app/Http/Controllers/Api/TenantController.php:11
* @route '/api/tenants'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/tenants',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\TenantController::index
* @see app/Http/Controllers/Api/TenantController.php:11
* @route '/api/tenants'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\TenantController::index
* @see app/Http/Controllers/Api/TenantController.php:11
* @route '/api/tenants'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\TenantController::index
* @see app/Http/Controllers/Api/TenantController.php:11
* @route '/api/tenants'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\TenantController::show
* @see app/Http/Controllers/Api/TenantController.php:16
* @route '/api/tenants/{id}'
*/
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/api/tenants/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\TenantController::show
* @see app/Http/Controllers/Api/TenantController.php:16
* @route '/api/tenants/{id}'
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
* @see \App\Http\Controllers\Api\TenantController::show
* @see app/Http/Controllers/Api/TenantController.php:16
* @route '/api/tenants/{id}'
*/
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\TenantController::show
* @see app/Http/Controllers/Api/TenantController.php:16
* @route '/api/tenants/{id}'
*/
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\TenantController::byDomain
* @see app/Http/Controllers/Api/TenantController.php:23
* @route '/api/tenants/by-domain'
*/
export const byDomain = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byDomain.url(options),
    method: 'get',
})

byDomain.definition = {
    methods: ["get","head"],
    url: '/api/tenants/by-domain',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\TenantController::byDomain
* @see app/Http/Controllers/Api/TenantController.php:23
* @route '/api/tenants/by-domain'
*/
byDomain.url = (options?: RouteQueryOptions) => {
    return byDomain.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\TenantController::byDomain
* @see app/Http/Controllers/Api/TenantController.php:23
* @route '/api/tenants/by-domain'
*/
byDomain.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byDomain.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\TenantController::byDomain
* @see app/Http/Controllers/Api/TenantController.php:23
* @route '/api/tenants/by-domain'
*/
byDomain.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: byDomain.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\TenantController::store
* @see app/Http/Controllers/Api/TenantController.php:31
* @route '/api/tenants'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/tenants',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\TenantController::store
* @see app/Http/Controllers/Api/TenantController.php:31
* @route '/api/tenants'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\TenantController::store
* @see app/Http/Controllers/Api/TenantController.php:31
* @route '/api/tenants'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\TenantController::update
* @see app/Http/Controllers/Api/TenantController.php:43
* @route '/api/tenants/{id}'
*/
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/api/tenants/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Api\TenantController::update
* @see app/Http/Controllers/Api/TenantController.php:43
* @route '/api/tenants/{id}'
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
* @see \App\Http\Controllers\Api\TenantController::update
* @see app/Http/Controllers/Api/TenantController.php:43
* @route '/api/tenants/{id}'
*/
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Api\TenantController::destroy
* @see app/Http/Controllers/Api/TenantController.php:60
* @route '/api/tenants/{id}'
*/
export const destroy = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/api/tenants/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Api\TenantController::destroy
* @see app/Http/Controllers/Api/TenantController.php:60
* @route '/api/tenants/{id}'
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
* @see \App\Http\Controllers\Api\TenantController::destroy
* @see app/Http/Controllers/Api/TenantController.php:60
* @route '/api/tenants/{id}'
*/
destroy.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const TenantController = { index, show, byDomain, store, update, destroy }

export default TenantController