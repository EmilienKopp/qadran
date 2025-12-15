import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\UserController::index
* @see app/Http/Controllers/Api/UserController.php:15
* @route '/api/users'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/users',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\UserController::index
* @see app/Http/Controllers/Api/UserController.php:15
* @route '/api/users'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\UserController::index
* @see app/Http/Controllers/Api/UserController.php:15
* @route '/api/users'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\UserController::index
* @see app/Http/Controllers/Api/UserController.php:15
* @route '/api/users'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\UserController::show
* @see app/Http/Controllers/Api/UserController.php:20
* @route '/api/users/{id}'
*/
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/api/users/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\UserController::show
* @see app/Http/Controllers/Api/UserController.php:20
* @route '/api/users/{id}'
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
* @see \App\Http\Controllers\Api\UserController::show
* @see app/Http/Controllers/Api/UserController.php:20
* @route '/api/users/{id}'
*/
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\UserController::show
* @see app/Http/Controllers/Api/UserController.php:20
* @route '/api/users/{id}'
*/
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\UserController::byWorkosId
* @see app/Http/Controllers/Api/UserController.php:27
* @route '/api/users/by-workos-id/{workosId}'
*/
export const byWorkosId = (args: { workosId: string | number } | [workosId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byWorkosId.url(args, options),
    method: 'get',
})

byWorkosId.definition = {
    methods: ["get","head"],
    url: '/api/users/by-workos-id/{workosId}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\UserController::byWorkosId
* @see app/Http/Controllers/Api/UserController.php:27
* @route '/api/users/by-workos-id/{workosId}'
*/
byWorkosId.url = (args: { workosId: string | number } | [workosId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { workosId: args }
    }

    if (Array.isArray(args)) {
        args = {
            workosId: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        workosId: args.workosId,
    }

    return byWorkosId.definition.url
            .replace('{workosId}', parsedArgs.workosId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\UserController::byWorkosId
* @see app/Http/Controllers/Api/UserController.php:27
* @route '/api/users/by-workos-id/{workosId}'
*/
byWorkosId.get = (args: { workosId: string | number } | [workosId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byWorkosId.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\UserController::byWorkosId
* @see app/Http/Controllers/Api/UserController.php:27
* @route '/api/users/by-workos-id/{workosId}'
*/
byWorkosId.head = (args: { workosId: string | number } | [workosId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: byWorkosId.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\UserController::byGitHubId
* @see app/Http/Controllers/Api/UserController.php:34
* @route '/api/users/by-github-id/{githubId}'
*/
export const byGitHubId = (args: { githubId: string | number } | [githubId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byGitHubId.url(args, options),
    method: 'get',
})

byGitHubId.definition = {
    methods: ["get","head"],
    url: '/api/users/by-github-id/{githubId}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\UserController::byGitHubId
* @see app/Http/Controllers/Api/UserController.php:34
* @route '/api/users/by-github-id/{githubId}'
*/
byGitHubId.url = (args: { githubId: string | number } | [githubId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { githubId: args }
    }

    if (Array.isArray(args)) {
        args = {
            githubId: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        githubId: args.githubId,
    }

    return byGitHubId.definition.url
            .replace('{githubId}', parsedArgs.githubId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\UserController::byGitHubId
* @see app/Http/Controllers/Api/UserController.php:34
* @route '/api/users/by-github-id/{githubId}'
*/
byGitHubId.get = (args: { githubId: string | number } | [githubId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byGitHubId.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\UserController::byGitHubId
* @see app/Http/Controllers/Api/UserController.php:34
* @route '/api/users/by-github-id/{githubId}'
*/
byGitHubId.head = (args: { githubId: string | number } | [githubId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: byGitHubId.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\UserController::byGoogleId
* @see app/Http/Controllers/Api/UserController.php:45
* @route '/api/users/by-google-id/{googleId}'
*/
export const byGoogleId = (args: { googleId: string | number } | [googleId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byGoogleId.url(args, options),
    method: 'get',
})

byGoogleId.definition = {
    methods: ["get","head"],
    url: '/api/users/by-google-id/{googleId}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\UserController::byGoogleId
* @see app/Http/Controllers/Api/UserController.php:45
* @route '/api/users/by-google-id/{googleId}'
*/
byGoogleId.url = (args: { googleId: string | number } | [googleId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { googleId: args }
    }

    if (Array.isArray(args)) {
        args = {
            googleId: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        googleId: args.googleId,
    }

    return byGoogleId.definition.url
            .replace('{googleId}', parsedArgs.googleId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\UserController::byGoogleId
* @see app/Http/Controllers/Api/UserController.php:45
* @route '/api/users/by-google-id/{googleId}'
*/
byGoogleId.get = (args: { googleId: string | number } | [googleId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byGoogleId.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\UserController::byGoogleId
* @see app/Http/Controllers/Api/UserController.php:45
* @route '/api/users/by-google-id/{googleId}'
*/
byGoogleId.head = (args: { googleId: string | number } | [googleId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: byGoogleId.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\UserController::byEmail
* @see app/Http/Controllers/Api/UserController.php:56
* @route '/api/users/by-email'
*/
export const byEmail = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byEmail.url(options),
    method: 'get',
})

byEmail.definition = {
    methods: ["get","head"],
    url: '/api/users/by-email',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\UserController::byEmail
* @see app/Http/Controllers/Api/UserController.php:56
* @route '/api/users/by-email'
*/
byEmail.url = (options?: RouteQueryOptions) => {
    return byEmail.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\UserController::byEmail
* @see app/Http/Controllers/Api/UserController.php:56
* @route '/api/users/by-email'
*/
byEmail.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: byEmail.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\UserController::byEmail
* @see app/Http/Controllers/Api/UserController.php:56
* @route '/api/users/by-email'
*/
byEmail.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: byEmail.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\UserController::store
* @see app/Http/Controllers/Api/UserController.php:64
* @route '/api/users'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/users',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\UserController::store
* @see app/Http/Controllers/Api/UserController.php:64
* @route '/api/users'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\UserController::store
* @see app/Http/Controllers/Api/UserController.php:64
* @route '/api/users'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\UserController::update
* @see app/Http/Controllers/Api/UserController.php:77
* @route '/api/users/{id}'
*/
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/api/users/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Api\UserController::update
* @see app/Http/Controllers/Api/UserController.php:77
* @route '/api/users/{id}'
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
* @see \App\Http\Controllers\Api\UserController::update
* @see app/Http/Controllers/Api/UserController.php:77
* @route '/api/users/{id}'
*/
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Api\UserController::destroy
* @see app/Http/Controllers/Api/UserController.php:93
* @route '/api/users/{id}'
*/
export const destroy = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/api/users/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Api\UserController::destroy
* @see app/Http/Controllers/Api/UserController.php:93
* @route '/api/users/{id}'
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
* @see \App\Http\Controllers\Api\UserController::destroy
* @see app/Http/Controllers/Api/UserController.php:93
* @route '/api/users/{id}'
*/
destroy.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const UserController = { index, show, byWorkosId, byGitHubId, byGoogleId, byEmail, store, update, destroy }

export default UserController