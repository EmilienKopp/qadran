import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ProjectController::index
* @see app/Http/Controllers/ProjectController.php:20
* @param account - Default: '$subdomain'
* @route '/{account?}/projects'
*/
export const index = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/{account?}/projects',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProjectController::index
* @see app/Http/Controllers/ProjectController.php:20
* @param account - Default: '$subdomain'
* @route '/{account?}/projects'
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
* @see \App\Http\Controllers\ProjectController::index
* @see app/Http/Controllers/ProjectController.php:20
* @param account - Default: '$subdomain'
* @route '/{account?}/projects'
*/
index.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProjectController::index
* @see app/Http/Controllers/ProjectController.php:20
* @param account - Default: '$subdomain'
* @route '/{account?}/projects'
*/
index.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProjectController::create
* @see app/Http/Controllers/ProjectController.php:34
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/create'
*/
export const create = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/{account?}/projects/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProjectController::create
* @see app/Http/Controllers/ProjectController.php:34
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/create'
*/
create.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return create.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProjectController::create
* @see app/Http/Controllers/ProjectController.php:34
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/create'
*/
create.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProjectController::create
* @see app/Http/Controllers/ProjectController.php:34
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/create'
*/
create.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProjectController::store
* @see app/Http/Controllers/ProjectController.php:42
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/store'
*/
export const store = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/{account?}/projects/store',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ProjectController::store
* @see app/Http/Controllers/ProjectController.php:42
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/store'
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
* @see \App\Http\Controllers\ProjectController::store
* @see app/Http/Controllers/ProjectController.php:42
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/store'
*/
store.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ProjectController::show
* @see app/Http/Controllers/ProjectController.php:53
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/{project}'
*/
export const show = (args: { account?: string | number, project: string | number | { id: string | number } } | [account: string | number, project: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/{account?}/projects/{project}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProjectController::show
* @see app/Http/Controllers/ProjectController.php:53
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/{project}'
*/
show.url = (args: { account?: string | number, project: string | number | { id: string | number } } | [account: string | number, project: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            project: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        project: typeof args.project === 'object'
        ? args.project.id
        : args.project,
    }

    return show.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{project}', parsedArgs.project.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProjectController::show
* @see app/Http/Controllers/ProjectController.php:53
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/{project}'
*/
show.get = (args: { account?: string | number, project: string | number | { id: string | number } } | [account: string | number, project: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProjectController::show
* @see app/Http/Controllers/ProjectController.php:53
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/{project}'
*/
show.head = (args: { account?: string | number, project: string | number | { id: string | number } } | [account: string | number, project: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProjectController::edit
* @see app/Http/Controllers/ProjectController.php:63
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/{project}/edit'
*/
export const edit = (args: { account?: string | number, project: string | number | { id: string | number } } | [account: string | number, project: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/{account?}/projects/{project}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProjectController::edit
* @see app/Http/Controllers/ProjectController.php:63
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/{project}/edit'
*/
edit.url = (args: { account?: string | number, project: string | number | { id: string | number } } | [account: string | number, project: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            project: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        project: typeof args.project === 'object'
        ? args.project.id
        : args.project,
    }

    return edit.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{project}', parsedArgs.project.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProjectController::edit
* @see app/Http/Controllers/ProjectController.php:63
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/{project}/edit'
*/
edit.get = (args: { account?: string | number, project: string | number | { id: string | number } } | [account: string | number, project: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProjectController::edit
* @see app/Http/Controllers/ProjectController.php:63
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/{project}/edit'
*/
edit.head = (args: { account?: string | number, project: string | number | { id: string | number } } | [account: string | number, project: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProjectController::update
* @see app/Http/Controllers/ProjectController.php:79
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/{project}'
*/
export const update = (args: { account?: string | number, project: string | number | { id: string | number } } | [account: string | number, project: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

update.definition = {
    methods: ["patch"],
    url: '/{account?}/projects/{project}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\ProjectController::update
* @see app/Http/Controllers/ProjectController.php:79
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/{project}'
*/
update.url = (args: { account?: string | number, project: string | number | { id: string | number } } | [account: string | number, project: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            project: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        project: typeof args.project === 'object'
        ? args.project.id
        : args.project,
    }

    return update.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{project}', parsedArgs.project.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProjectController::update
* @see app/Http/Controllers/ProjectController.php:79
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/{project}'
*/
update.patch = (args: { account?: string | number, project: string | number | { id: string | number } } | [account: string | number, project: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\ProjectController::destroy
* @see app/Http/Controllers/ProjectController.php:87
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/{project}'
*/
export const destroy = (args: { account?: string | number, project: string | number | { id: string | number } } | [account: string | number, project: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/{account?}/projects/{project}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ProjectController::destroy
* @see app/Http/Controllers/ProjectController.php:87
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/{project}'
*/
destroy.url = (args: { account?: string | number, project: string | number | { id: string | number } } | [account: string | number, project: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            project: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        project: typeof args.project === 'object'
        ? args.project.id
        : args.project,
    }

    return destroy.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{project}', parsedArgs.project.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProjectController::destroy
* @see app/Http/Controllers/ProjectController.php:87
* @param account - Default: '$subdomain'
* @route '/{account?}/projects/{project}'
*/
destroy.delete = (args: { account?: string | number, project: string | number | { id: string | number } } | [account: string | number, project: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const ProjectController = { index, create, store, show, edit, update, destroy }

export default ProjectController