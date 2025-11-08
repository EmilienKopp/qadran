import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\ProjectController::index
* @see app/Http/Controllers/Api/ProjectController.php:15
* @route '/api/projects'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/projects',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ProjectController::index
* @see app/Http/Controllers/Api/ProjectController.php:15
* @route '/api/projects'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ProjectController::index
* @see app/Http/Controllers/Api/ProjectController.php:15
* @route '/api/projects'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ProjectController::index
* @see app/Http/Controllers/Api/ProjectController.php:15
* @route '/api/projects'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\ProjectController::show
* @see app/Http/Controllers/Api/ProjectController.php:20
* @route '/api/projects/{id}'
*/
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/api/projects/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ProjectController::show
* @see app/Http/Controllers/Api/ProjectController.php:20
* @route '/api/projects/{id}'
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
* @see \App\Http\Controllers\Api\ProjectController::show
* @see app/Http/Controllers/Api/ProjectController.php:20
* @route '/api/projects/{id}'
*/
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ProjectController::show
* @see app/Http/Controllers/Api/ProjectController.php:20
* @route '/api/projects/{id}'
*/
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\ProjectController::byOrganization
* @see app/Http/Controllers/Api/ProjectController.php:26
* @route '/api/projects/by-organization/{organizationId}'
*/
export const byOrganization = (args: { organizationId: string | number } | [organizationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byOrganization.url(args, options),
    method: 'get',
})

byOrganization.definition = {
    methods: ["get","head"],
    url: '/api/projects/by-organization/{organizationId}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ProjectController::byOrganization
* @see app/Http/Controllers/Api/ProjectController.php:26
* @route '/api/projects/by-organization/{organizationId}'
*/
byOrganization.url = (args: { organizationId: string | number } | [organizationId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { organizationId: args }
    }

    if (Array.isArray(args)) {
        args = {
            organizationId: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        organizationId: args.organizationId,
    }

    return byOrganization.definition.url
            .replace('{organizationId}', parsedArgs.organizationId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ProjectController::byOrganization
* @see app/Http/Controllers/Api/ProjectController.php:26
* @route '/api/projects/by-organization/{organizationId}'
*/
byOrganization.get = (args: { organizationId: string | number } | [organizationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byOrganization.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ProjectController::byOrganization
* @see app/Http/Controllers/Api/ProjectController.php:26
* @route '/api/projects/by-organization/{organizationId}'
*/
byOrganization.head = (args: { organizationId: string | number } | [organizationId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: byOrganization.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\ProjectController::store
* @see app/Http/Controllers/Api/ProjectController.php:32
* @route '/api/projects'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/projects',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\ProjectController::store
* @see app/Http/Controllers/Api/ProjectController.php:32
* @route '/api/projects'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ProjectController::store
* @see app/Http/Controllers/Api/ProjectController.php:32
* @route '/api/projects'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\ProjectController::update
* @see app/Http/Controllers/Api/ProjectController.php:43
* @route '/api/projects/{id}'
*/
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/api/projects/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Api\ProjectController::update
* @see app/Http/Controllers/Api/ProjectController.php:43
* @route '/api/projects/{id}'
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
* @see \App\Http\Controllers\Api\ProjectController::update
* @see app/Http/Controllers/Api/ProjectController.php:43
* @route '/api/projects/{id}'
*/
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Api\ProjectController::destroy
* @see app/Http/Controllers/Api/ProjectController.php:57
* @route '/api/projects/{id}'
*/
export const destroy = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/api/projects/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Api\ProjectController::destroy
* @see app/Http/Controllers/Api/ProjectController.php:57
* @route '/api/projects/{id}'
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
* @see \App\Http\Controllers\Api\ProjectController::destroy
* @see app/Http/Controllers/Api/ProjectController.php:57
* @route '/api/projects/{id}'
*/
destroy.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const ProjectController = { index, show, byOrganization, store, update, destroy }

export default ProjectController