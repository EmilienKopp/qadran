import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\RateController::index
* @see app/Http/Controllers/RateController.php:18
* @route '/rates'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/rates',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RateController::index
* @see app/Http/Controllers/RateController.php:18
* @route '/rates'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\RateController::index
* @see app/Http/Controllers/RateController.php:18
* @route '/rates'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\RateController::index
* @see app/Http/Controllers/RateController.php:18
* @route '/rates'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\RateController::create
* @see app/Http/Controllers/RateController.php:32
* @route '/rates/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/rates/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RateController::create
* @see app/Http/Controllers/RateController.php:32
* @route '/rates/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\RateController::create
* @see app/Http/Controllers/RateController.php:32
* @route '/rates/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\RateController::create
* @see app/Http/Controllers/RateController.php:32
* @route '/rates/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\RateController::store
* @see app/Http/Controllers/RateController.php:46
* @route '/rates/store'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/rates/store',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\RateController::store
* @see app/Http/Controllers/RateController.php:46
* @route '/rates/store'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\RateController::store
* @see app/Http/Controllers/RateController.php:46
* @route '/rates/store'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\RateController::show
* @see app/Http/Controllers/RateController.php:78
* @route '/rates/{rate}'
*/
export const show = (args: { rate: number | { id: number } } | [rate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/rates/{rate}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RateController::show
* @see app/Http/Controllers/RateController.php:78
* @route '/rates/{rate}'
*/
show.url = (args: { rate: number | { id: number } } | [rate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { rate: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { rate: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            rate: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        rate: typeof args.rate === 'object'
        ? args.rate.id
        : args.rate,
    }

    return show.definition.url
            .replace('{rate}', parsedArgs.rate.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RateController::show
* @see app/Http/Controllers/RateController.php:78
* @route '/rates/{rate}'
*/
show.get = (args: { rate: number | { id: number } } | [rate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\RateController::show
* @see app/Http/Controllers/RateController.php:78
* @route '/rates/{rate}'
*/
show.head = (args: { rate: number | { id: number } } | [rate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\RateController::edit
* @see app/Http/Controllers/RateController.php:90
* @route '/rates/{rate}/edit'
*/
export const edit = (args: { rate: number | { id: number } } | [rate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/rates/{rate}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RateController::edit
* @see app/Http/Controllers/RateController.php:90
* @route '/rates/{rate}/edit'
*/
edit.url = (args: { rate: number | { id: number } } | [rate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { rate: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { rate: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            rate: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        rate: typeof args.rate === 'object'
        ? args.rate.id
        : args.rate,
    }

    return edit.definition.url
            .replace('{rate}', parsedArgs.rate.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RateController::edit
* @see app/Http/Controllers/RateController.php:90
* @route '/rates/{rate}/edit'
*/
edit.get = (args: { rate: number | { id: number } } | [rate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\RateController::edit
* @see app/Http/Controllers/RateController.php:90
* @route '/rates/{rate}/edit'
*/
edit.head = (args: { rate: number | { id: number } } | [rate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\RateController::update
* @see app/Http/Controllers/RateController.php:104
* @route '/rates/{rate}'
*/
export const update = (args: { rate: number | { id: number } } | [rate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

update.definition = {
    methods: ["patch"],
    url: '/rates/{rate}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\RateController::update
* @see app/Http/Controllers/RateController.php:104
* @route '/rates/{rate}'
*/
update.url = (args: { rate: number | { id: number } } | [rate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { rate: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { rate: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            rate: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        rate: typeof args.rate === 'object'
        ? args.rate.id
        : args.rate,
    }

    return update.definition.url
            .replace('{rate}', parsedArgs.rate.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RateController::update
* @see app/Http/Controllers/RateController.php:104
* @route '/rates/{rate}'
*/
update.patch = (args: { rate: number | { id: number } } | [rate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\RateController::destroy
* @see app/Http/Controllers/RateController.php:134
* @route '/rates/{rate}'
*/
export const destroy = (args: { rate: number | { id: number } } | [rate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/rates/{rate}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\RateController::destroy
* @see app/Http/Controllers/RateController.php:134
* @route '/rates/{rate}'
*/
destroy.url = (args: { rate: number | { id: number } } | [rate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { rate: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { rate: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            rate: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        rate: typeof args.rate === 'object'
        ? args.rate.id
        : args.rate,
    }

    return destroy.definition.url
            .replace('{rate}', parsedArgs.rate.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RateController::destroy
* @see app/Http/Controllers/RateController.php:134
* @route '/rates/{rate}'
*/
destroy.delete = (args: { rate: number | { id: number } } | [rate: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const RateController = { index, create, store, show, edit, update, destroy }

export default RateController