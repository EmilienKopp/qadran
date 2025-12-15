import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../wayfinder'
/**
* @see \App\Http\Controllers\OrganizationController::index
* @see app/Http/Controllers/OrganizationController.php:17
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations'
*/
export const index = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/{account?}/organizations',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OrganizationController::index
* @see app/Http/Controllers/OrganizationController.php:17
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations'
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
* @see \App\Http\Controllers\OrganizationController::index
* @see app/Http/Controllers/OrganizationController.php:17
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations'
*/
index.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OrganizationController::index
* @see app/Http/Controllers/OrganizationController.php:17
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations'
*/
index.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OrganizationController::create
* @see app/Http/Controllers/OrganizationController.php:29
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/create'
*/
export const create = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/{account?}/organizations/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OrganizationController::create
* @see app/Http/Controllers/OrganizationController.php:29
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/create'
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
* @see \App\Http\Controllers\OrganizationController::create
* @see app/Http/Controllers/OrganizationController.php:29
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/create'
*/
create.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OrganizationController::create
* @see app/Http/Controllers/OrganizationController.php:29
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/create'
*/
create.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OrganizationController::store
* @see app/Http/Controllers/OrganizationController.php:39
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/store'
*/
export const store = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/{account?}/organizations/store',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\OrganizationController::store
* @see app/Http/Controllers/OrganizationController.php:39
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/store'
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
* @see \App\Http\Controllers\OrganizationController::store
* @see app/Http/Controllers/OrganizationController.php:39
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/store'
*/
store.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OrganizationController::show
* @see app/Http/Controllers/OrganizationController.php:52
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/{organization}'
*/
export const show = (args: { account?: string | number, organization: string | number | { id: string | number } } | [account: string | number, organization: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/{account?}/organizations/{organization}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OrganizationController::show
* @see app/Http/Controllers/OrganizationController.php:52
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/{organization}'
*/
show.url = (args: { account?: string | number, organization: string | number | { id: string | number } } | [account: string | number, organization: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            organization: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        organization: typeof args.organization === 'object'
        ? args.organization.id
        : args.organization,
    }

    return show.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{organization}', parsedArgs.organization.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OrganizationController::show
* @see app/Http/Controllers/OrganizationController.php:52
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/{organization}'
*/
show.get = (args: { account?: string | number, organization: string | number | { id: string | number } } | [account: string | number, organization: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OrganizationController::show
* @see app/Http/Controllers/OrganizationController.php:52
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/{organization}'
*/
show.head = (args: { account?: string | number, organization: string | number | { id: string | number } } | [account: string | number, organization: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OrganizationController::edit
* @see app/Http/Controllers/OrganizationController.php:60
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/{organization}/edit'
*/
export const edit = (args: { account?: string | number, organization: string | number | { id: string | number } } | [account: string | number, organization: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/{account?}/organizations/{organization}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OrganizationController::edit
* @see app/Http/Controllers/OrganizationController.php:60
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/{organization}/edit'
*/
edit.url = (args: { account?: string | number, organization: string | number | { id: string | number } } | [account: string | number, organization: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            organization: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        organization: typeof args.organization === 'object'
        ? args.organization.id
        : args.organization,
    }

    return edit.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{organization}', parsedArgs.organization.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OrganizationController::edit
* @see app/Http/Controllers/OrganizationController.php:60
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/{organization}/edit'
*/
edit.get = (args: { account?: string | number, organization: string | number | { id: string | number } } | [account: string | number, organization: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\OrganizationController::edit
* @see app/Http/Controllers/OrganizationController.php:60
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/{organization}/edit'
*/
edit.head = (args: { account?: string | number, organization: string | number | { id: string | number } } | [account: string | number, organization: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OrganizationController::update
* @see app/Http/Controllers/OrganizationController.php:71
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/{organization}'
*/
export const update = (args: { account?: string | number, organization: string | number | { id: string | number } } | [account: string | number, organization: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

update.definition = {
    methods: ["patch"],
    url: '/{account?}/organizations/{organization}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\OrganizationController::update
* @see app/Http/Controllers/OrganizationController.php:71
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/{organization}'
*/
update.url = (args: { account?: string | number, organization: string | number | { id: string | number } } | [account: string | number, organization: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            organization: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        organization: typeof args.organization === 'object'
        ? args.organization.id
        : args.organization,
    }

    return update.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{organization}', parsedArgs.organization.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OrganizationController::update
* @see app/Http/Controllers/OrganizationController.php:71
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/{organization}'
*/
update.patch = (args: { account?: string | number, organization: string | number | { id: string | number } } | [account: string | number, organization: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\OrganizationController::destroy
* @see app/Http/Controllers/OrganizationController.php:82
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/{organization}'
*/
export const destroy = (args: { account?: string | number, organization: string | number | { id: string | number } } | [account: string | number, organization: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/{account?}/organizations/{organization}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\OrganizationController::destroy
* @see app/Http/Controllers/OrganizationController.php:82
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/{organization}'
*/
destroy.url = (args: { account?: string | number, organization: string | number | { id: string | number } } | [account: string | number, organization: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            organization: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        organization: typeof args.organization === 'object'
        ? args.organization.id
        : args.organization,
    }

    return destroy.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{organization}', parsedArgs.organization.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OrganizationController::destroy
* @see app/Http/Controllers/OrganizationController.php:82
* @param account - Default: '$subdomain'
* @route '/{account?}/organizations/{organization}'
*/
destroy.delete = (args: { account?: string | number, organization: string | number | { id: string | number } } | [account: string | number, organization: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const organization = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default organization