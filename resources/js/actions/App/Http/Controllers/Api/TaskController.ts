import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\TaskController::index
* @see app/Http/Controllers/Api/TaskController.php:15
* @route '/api/tasks'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/tasks',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\TaskController::index
* @see app/Http/Controllers/Api/TaskController.php:15
* @route '/api/tasks'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\TaskController::index
* @see app/Http/Controllers/Api/TaskController.php:15
* @route '/api/tasks'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\TaskController::index
* @see app/Http/Controllers/Api/TaskController.php:15
* @route '/api/tasks'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\TaskController::show
* @see app/Http/Controllers/Api/TaskController.php:20
* @route '/api/tasks/{id}'
*/
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/api/tasks/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\TaskController::show
* @see app/Http/Controllers/Api/TaskController.php:20
* @route '/api/tasks/{id}'
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
* @see \App\Http\Controllers\Api\TaskController::show
* @see app/Http/Controllers/Api/TaskController.php:20
* @route '/api/tasks/{id}'
*/
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\TaskController::show
* @see app/Http/Controllers/Api/TaskController.php:20
* @route '/api/tasks/{id}'
*/
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\TaskController::byProject
* @see app/Http/Controllers/Api/TaskController.php:27
* @route '/api/tasks/by-project/{projectId}'
*/
export const byProject = (args: { projectId: string | number } | [projectId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byProject.url(args, options),
    method: 'get',
})

byProject.definition = {
    methods: ["get","head"],
    url: '/api/tasks/by-project/{projectId}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\TaskController::byProject
* @see app/Http/Controllers/Api/TaskController.php:27
* @route '/api/tasks/by-project/{projectId}'
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
* @see \App\Http\Controllers\Api\TaskController::byProject
* @see app/Http/Controllers/Api/TaskController.php:27
* @route '/api/tasks/by-project/{projectId}'
*/
byProject.get = (args: { projectId: string | number } | [projectId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byProject.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\TaskController::byProject
* @see app/Http/Controllers/Api/TaskController.php:27
* @route '/api/tasks/by-project/{projectId}'
*/
byProject.head = (args: { projectId: string | number } | [projectId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: byProject.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\TaskController::store
* @see app/Http/Controllers/Api/TaskController.php:34
* @route '/api/tasks'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/tasks',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\TaskController::store
* @see app/Http/Controllers/Api/TaskController.php:34
* @route '/api/tasks'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\TaskController::store
* @see app/Http/Controllers/Api/TaskController.php:34
* @route '/api/tasks'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\TaskController::update
* @see app/Http/Controllers/Api/TaskController.php:46
* @route '/api/tasks/{id}'
*/
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/api/tasks/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Api\TaskController::update
* @see app/Http/Controllers/Api/TaskController.php:46
* @route '/api/tasks/{id}'
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
* @see \App\Http\Controllers\Api\TaskController::update
* @see app/Http/Controllers/Api/TaskController.php:46
* @route '/api/tasks/{id}'
*/
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Api\TaskController::destroy
* @see app/Http/Controllers/Api/TaskController.php:61
* @route '/api/tasks/{id}'
*/
export const destroy = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/api/tasks/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Api\TaskController::destroy
* @see app/Http/Controllers/Api/TaskController.php:61
* @route '/api/tasks/{id}'
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
* @see \App\Http\Controllers\Api\TaskController::destroy
* @see app/Http/Controllers/Api/TaskController.php:61
* @route '/api/tasks/{id}'
*/
destroy.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const TaskController = { index, show, byProject, store, update, destroy }

export default TaskController