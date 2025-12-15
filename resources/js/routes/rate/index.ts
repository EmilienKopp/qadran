import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../wayfinder'
/**
* @see \App\Http\Controllers\RateController::index
* @see app/Http/Controllers/RateController.php:19
* @param account - Default: '$subdomain'
* @route '/{account?}/rates'
*/
export const index = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/{account?}/rates',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RateController::index
* @see app/Http/Controllers/RateController.php:19
* @param account - Default: '$subdomain'
* @route '/{account?}/rates'
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
* @see \App\Http\Controllers\RateController::index
* @see app/Http/Controllers/RateController.php:19
* @param account - Default: '$subdomain'
* @route '/{account?}/rates'
*/
index.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\RateController::index
* @see app/Http/Controllers/RateController.php:19
* @param account - Default: '$subdomain'
* @route '/{account?}/rates'
*/
index.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\RateController::create
* @see app/Http/Controllers/RateController.php:33
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/create'
*/
export const create = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/{account?}/rates/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RateController::create
* @see app/Http/Controllers/RateController.php:33
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/create'
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
* @see \App\Http\Controllers\RateController::create
* @see app/Http/Controllers/RateController.php:33
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/create'
*/
create.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\RateController::create
* @see app/Http/Controllers/RateController.php:33
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/create'
*/
create.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\RateController::store
* @see app/Http/Controllers/RateController.php:47
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/store'
*/
export const store = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/{account?}/rates/store',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\RateController::store
* @see app/Http/Controllers/RateController.php:47
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/store'
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
* @see \App\Http\Controllers\RateController::store
* @see app/Http/Controllers/RateController.php:47
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/store'
*/
store.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\RateController::show
* @see app/Http/Controllers/RateController.php:79
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/{rate}'
*/
export const show = (args: { account?: string | number, rate: string | number | { id: string | number } } | [account: string | number, rate: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/{account?}/rates/{rate}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RateController::show
* @see app/Http/Controllers/RateController.php:79
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/{rate}'
*/
show.url = (args: { account?: string | number, rate: string | number | { id: string | number } } | [account: string | number, rate: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            rate: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        rate: typeof args.rate === 'object'
        ? args.rate.id
        : args.rate,
    }

    return show.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{rate}', parsedArgs.rate.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RateController::show
* @see app/Http/Controllers/RateController.php:79
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/{rate}'
*/
show.get = (args: { account?: string | number, rate: string | number | { id: string | number } } | [account: string | number, rate: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\RateController::show
* @see app/Http/Controllers/RateController.php:79
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/{rate}'
*/
show.head = (args: { account?: string | number, rate: string | number | { id: string | number } } | [account: string | number, rate: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\RateController::edit
* @see app/Http/Controllers/RateController.php:91
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/{rate}/edit'
*/
export const edit = (args: { account?: string | number, rate: string | number | { id: string | number } } | [account: string | number, rate: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/{account?}/rates/{rate}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RateController::edit
* @see app/Http/Controllers/RateController.php:91
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/{rate}/edit'
*/
edit.url = (args: { account?: string | number, rate: string | number | { id: string | number } } | [account: string | number, rate: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            rate: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        rate: typeof args.rate === 'object'
        ? args.rate.id
        : args.rate,
    }

    return edit.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{rate}', parsedArgs.rate.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RateController::edit
* @see app/Http/Controllers/RateController.php:91
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/{rate}/edit'
*/
edit.get = (args: { account?: string | number, rate: string | number | { id: string | number } } | [account: string | number, rate: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\RateController::edit
* @see app/Http/Controllers/RateController.php:91
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/{rate}/edit'
*/
edit.head = (args: { account?: string | number, rate: string | number | { id: string | number } } | [account: string | number, rate: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\RateController::update
* @see app/Http/Controllers/RateController.php:105
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/{rate}'
*/
export const update = (args: { account?: string | number, rate: string | number | { id: string | number } } | [account: string | number, rate: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

update.definition = {
    methods: ["patch"],
    url: '/{account?}/rates/{rate}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\RateController::update
* @see app/Http/Controllers/RateController.php:105
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/{rate}'
*/
update.url = (args: { account?: string | number, rate: string | number | { id: string | number } } | [account: string | number, rate: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            rate: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        rate: typeof args.rate === 'object'
        ? args.rate.id
        : args.rate,
    }

    return update.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{rate}', parsedArgs.rate.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RateController::update
* @see app/Http/Controllers/RateController.php:105
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/{rate}'
*/
update.patch = (args: { account?: string | number, rate: string | number | { id: string | number } } | [account: string | number, rate: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\RateController::destroy
* @see app/Http/Controllers/RateController.php:135
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/{rate}'
*/
export const destroy = (args: { account?: string | number, rate: string | number | { id: string | number } } | [account: string | number, rate: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/{account?}/rates/{rate}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\RateController::destroy
* @see app/Http/Controllers/RateController.php:135
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/{rate}'
*/
destroy.url = (args: { account?: string | number, rate: string | number | { id: string | number } } | [account: string | number, rate: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            rate: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        rate: typeof args.rate === 'object'
        ? args.rate.id
        : args.rate,
    }

    return destroy.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{rate}', parsedArgs.rate.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RateController::destroy
* @see app/Http/Controllers/RateController.php:135
* @param account - Default: '$subdomain'
* @route '/{account?}/rates/{rate}'
*/
destroy.delete = (args: { account?: string | number, rate: string | number | { id: string | number } } | [account: string | number, rate: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const rate = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default rate