import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../wayfinder'
/**
* @see \App\Http\Controllers\ReportController::index
* @see app/Http/Controllers/ReportController.php:45
* @param account - Default: '$subdomain'
* @route '/{account?}/report'
*/
export const index = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/{account?}/report',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ReportController::index
* @see app/Http/Controllers/ReportController.php:45
* @param account - Default: '$subdomain'
* @route '/{account?}/report'
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
* @see \App\Http\Controllers\ReportController::index
* @see app/Http/Controllers/ReportController.php:45
* @param account - Default: '$subdomain'
* @route '/{account?}/report'
*/
index.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ReportController::index
* @see app/Http/Controllers/ReportController.php:45
* @param account - Default: '$subdomain'
* @route '/{account?}/report'
*/
index.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ReportController::create
* @see app/Http/Controllers/ReportController.php:60
* @param account - Default: '$subdomain'
* @route '/{account?}/report/create'
*/
export const create = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/{account?}/report/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ReportController::create
* @see app/Http/Controllers/ReportController.php:60
* @param account - Default: '$subdomain'
* @route '/{account?}/report/create'
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
* @see \App\Http\Controllers\ReportController::create
* @see app/Http/Controllers/ReportController.php:60
* @param account - Default: '$subdomain'
* @route '/{account?}/report/create'
*/
create.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ReportController::create
* @see app/Http/Controllers/ReportController.php:60
* @param account - Default: '$subdomain'
* @route '/{account?}/report/create'
*/
create.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ReportController::store
* @see app/Http/Controllers/ReportController.php:86
* @param account - Default: '$subdomain'
* @route '/{account?}/report/store'
*/
export const store = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/{account?}/report/store',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ReportController::store
* @see app/Http/Controllers/ReportController.php:86
* @param account - Default: '$subdomain'
* @route '/{account?}/report/store'
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
* @see \App\Http\Controllers\ReportController::store
* @see app/Http/Controllers/ReportController.php:86
* @param account - Default: '$subdomain'
* @route '/{account?}/report/store'
*/
store.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ReportController::generate
* @see app/Http/Controllers/ReportController.php:21
* @param account - Default: '$subdomain'
* @route '/{account?}/report/generate'
*/
export const generate = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: generate.url(args, options),
    method: 'post',
})

generate.definition = {
    methods: ["post"],
    url: '/{account?}/report/generate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ReportController::generate
* @see app/Http/Controllers/ReportController.php:21
* @param account - Default: '$subdomain'
* @route '/{account?}/report/generate'
*/
generate.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return generate.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::generate
* @see app/Http/Controllers/ReportController.php:21
* @param account - Default: '$subdomain'
* @route '/{account?}/report/generate'
*/
generate.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: generate.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ReportController::fetchCommits
* @see app/Http/Controllers/ReportController.php:107
* @param account - Default: '$subdomain'
* @route '/{account?}/report/logs'
*/
export const fetchCommits = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: fetchCommits.url(args, options),
    method: 'post',
})

fetchCommits.definition = {
    methods: ["post"],
    url: '/{account?}/report/logs',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ReportController::fetchCommits
* @see app/Http/Controllers/ReportController.php:107
* @param account - Default: '$subdomain'
* @route '/{account?}/report/logs'
*/
fetchCommits.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return fetchCommits.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::fetchCommits
* @see app/Http/Controllers/ReportController.php:107
* @param account - Default: '$subdomain'
* @route '/{account?}/report/logs'
*/
fetchCommits.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: fetchCommits.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ReportController::bustCache
* @see app/Http/Controllers/ReportController.php:165
* @param account - Default: '$subdomain'
* @route '/{account?}/report/bust-cache'
*/
export const bustCache = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bustCache.url(args, options),
    method: 'post',
})

bustCache.definition = {
    methods: ["post"],
    url: '/{account?}/report/bust-cache',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ReportController::bustCache
* @see app/Http/Controllers/ReportController.php:165
* @param account - Default: '$subdomain'
* @route '/{account?}/report/bust-cache'
*/
bustCache.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return bustCache.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::bustCache
* @see app/Http/Controllers/ReportController.php:165
* @param account - Default: '$subdomain'
* @route '/{account?}/report/bust-cache'
*/
bustCache.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bustCache.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ReportController::show
* @see app/Http/Controllers/ReportController.php:134
* @param account - Default: '$subdomain'
* @route '/{account?}/report/{report}'
*/
export const show = (args: { account?: string | number, report: string | number | { id: string | number } } | [account: string | number, report: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/{account?}/report/{report}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ReportController::show
* @see app/Http/Controllers/ReportController.php:134
* @param account - Default: '$subdomain'
* @route '/{account?}/report/{report}'
*/
show.url = (args: { account?: string | number, report: string | number | { id: string | number } } | [account: string | number, report: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            report: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        report: typeof args.report === 'object'
        ? args.report.id
        : args.report,
    }

    return show.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{report}', parsedArgs.report.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::show
* @see app/Http/Controllers/ReportController.php:134
* @param account - Default: '$subdomain'
* @route '/{account?}/report/{report}'
*/
show.get = (args: { account?: string | number, report: string | number | { id: string | number } } | [account: string | number, report: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ReportController::show
* @see app/Http/Controllers/ReportController.php:134
* @param account - Default: '$subdomain'
* @route '/{account?}/report/{report}'
*/
show.head = (args: { account?: string | number, report: string | number | { id: string | number } } | [account: string | number, report: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ReportController::edit
* @see app/Http/Controllers/ReportController.php:142
* @param account - Default: '$subdomain'
* @route '/{account?}/report/{report}/edit'
*/
export const edit = (args: { account?: string | number, report: string | number | { id: string | number } } | [account: string | number, report: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/{account?}/report/{report}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ReportController::edit
* @see app/Http/Controllers/ReportController.php:142
* @param account - Default: '$subdomain'
* @route '/{account?}/report/{report}/edit'
*/
edit.url = (args: { account?: string | number, report: string | number | { id: string | number } } | [account: string | number, report: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            report: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        report: typeof args.report === 'object'
        ? args.report.id
        : args.report,
    }

    return edit.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{report}', parsedArgs.report.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::edit
* @see app/Http/Controllers/ReportController.php:142
* @param account - Default: '$subdomain'
* @route '/{account?}/report/{report}/edit'
*/
edit.get = (args: { account?: string | number, report: string | number | { id: string | number } } | [account: string | number, report: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ReportController::edit
* @see app/Http/Controllers/ReportController.php:142
* @param account - Default: '$subdomain'
* @route '/{account?}/report/{report}/edit'
*/
edit.head = (args: { account?: string | number, report: string | number | { id: string | number } } | [account: string | number, report: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ReportController::update
* @see app/Http/Controllers/ReportController.php:152
* @param account - Default: '$subdomain'
* @route '/{account?}/report/{report}'
*/
export const update = (args: { account?: string | number, report: string | number | { id: string | number } } | [account: string | number, report: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

update.definition = {
    methods: ["patch"],
    url: '/{account?}/report/{report}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\ReportController::update
* @see app/Http/Controllers/ReportController.php:152
* @param account - Default: '$subdomain'
* @route '/{account?}/report/{report}'
*/
update.url = (args: { account?: string | number, report: string | number | { id: string | number } } | [account: string | number, report: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            report: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        report: typeof args.report === 'object'
        ? args.report.id
        : args.report,
    }

    return update.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{report}', parsedArgs.report.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::update
* @see app/Http/Controllers/ReportController.php:152
* @param account - Default: '$subdomain'
* @route '/{account?}/report/{report}'
*/
update.patch = (args: { account?: string | number, report: string | number | { id: string | number } } | [account: string | number, report: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\ReportController::destroy
* @see app/Http/Controllers/ReportController.php:160
* @param account - Default: '$subdomain'
* @route '/{account?}/report/{report}'
*/
export const destroy = (args: { account?: string | number, report: string | number | { id: string | number } } | [account: string | number, report: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/{account?}/report/{report}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ReportController::destroy
* @see app/Http/Controllers/ReportController.php:160
* @param account - Default: '$subdomain'
* @route '/{account?}/report/{report}'
*/
destroy.url = (args: { account?: string | number, report: string | number | { id: string | number } } | [account: string | number, report: string | number | { id: string | number } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            report: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        report: typeof args.report === 'object'
        ? args.report.id
        : args.report,
    }

    return destroy.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{report}', parsedArgs.report.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::destroy
* @see app/Http/Controllers/ReportController.php:160
* @param account - Default: '$subdomain'
* @route '/{account?}/report/{report}'
*/
destroy.delete = (args: { account?: string | number, report: string | number | { id: string | number } } | [account: string | number, report: string | number | { id: string | number } ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const report = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    generate: Object.assign(generate, generate),
    fetchCommits: Object.assign(fetchCommits, fetchCommits),
    bustCache: Object.assign(bustCache, bustCache),
    show: Object.assign(show, show),
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default report