import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\ReportController::index
* @see app/Http/Controllers/Api/ReportController.php:15
* @route '/api/reports'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/reports',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ReportController::index
* @see app/Http/Controllers/Api/ReportController.php:15
* @route '/api/reports'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ReportController::index
* @see app/Http/Controllers/Api/ReportController.php:15
* @route '/api/reports'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ReportController::index
* @see app/Http/Controllers/Api/ReportController.php:15
* @route '/api/reports'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\ReportController::show
* @see app/Http/Controllers/Api/ReportController.php:20
* @route '/api/reports/{id}'
*/
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/api/reports/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ReportController::show
* @see app/Http/Controllers/Api/ReportController.php:20
* @route '/api/reports/{id}'
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
* @see \App\Http\Controllers\Api\ReportController::show
* @see app/Http/Controllers/Api/ReportController.php:20
* @route '/api/reports/{id}'
*/
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ReportController::show
* @see app/Http/Controllers/Api/ReportController.php:20
* @route '/api/reports/{id}'
*/
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\ReportController::byProject
* @see app/Http/Controllers/Api/ReportController.php:27
* @route '/api/reports/by-project/{projectId}'
*/
export const byProject = (args: { projectId: string | number } | [projectId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byProject.url(args, options),
    method: 'get',
})

byProject.definition = {
    methods: ["get","head"],
    url: '/api/reports/by-project/{projectId}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ReportController::byProject
* @see app/Http/Controllers/Api/ReportController.php:27
* @route '/api/reports/by-project/{projectId}'
*/
byProject.url = (args: { projectId: string | number } | [projectId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { projectId: args }
    }

    if (Array.isArray(args)) {
        args = {
            projectId: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        projectId: args.projectId,
    }

    return byProject.definition.url
            .replace('{projectId}', parsedArgs.projectId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ReportController::byProject
* @see app/Http/Controllers/Api/ReportController.php:27
* @route '/api/reports/by-project/{projectId}'
*/
byProject.get = (args: { projectId: string | number } | [projectId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byProject.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ReportController::byProject
* @see app/Http/Controllers/Api/ReportController.php:27
* @route '/api/reports/by-project/{projectId}'
*/
byProject.head = (args: { projectId: string | number } | [projectId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: byProject.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\ReportController::store
* @see app/Http/Controllers/Api/ReportController.php:34
* @route '/api/reports'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/reports',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\ReportController::store
* @see app/Http/Controllers/Api/ReportController.php:34
* @route '/api/reports'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ReportController::store
* @see app/Http/Controllers/Api/ReportController.php:34
* @route '/api/reports'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\ReportController::update
* @see app/Http/Controllers/Api/ReportController.php:45
* @route '/api/reports/{id}'
*/
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/api/reports/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Api\ReportController::update
* @see app/Http/Controllers/Api/ReportController.php:45
* @route '/api/reports/{id}'
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
* @see \App\Http\Controllers\Api\ReportController::update
* @see app/Http/Controllers/Api/ReportController.php:45
* @route '/api/reports/{id}'
*/
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Api\ReportController::destroy
* @see app/Http/Controllers/Api/ReportController.php:59
* @route '/api/reports/{id}'
*/
export const destroy = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/api/reports/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Api\ReportController::destroy
* @see app/Http/Controllers/Api/ReportController.php:59
* @route '/api/reports/{id}'
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
* @see \App\Http\Controllers\Api\ReportController::destroy
* @see app/Http/Controllers/Api/ReportController.php:59
* @route '/api/reports/{id}'
*/
destroy.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const ReportController = { index, show, byProject, store, update, destroy }

export default ReportController