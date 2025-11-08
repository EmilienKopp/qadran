import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\ActivityController::show
* @see app/Http/Controllers/ActivityController.php:68
* @route '/activities/{date}'
*/
export const show = (args: { date: string | number } | [date: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/activities/{date}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ActivityController::show
* @see app/Http/Controllers/ActivityController.php:68
* @route '/activities/{date}'
*/
show.url = (args: { date: string | number } | [date: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { date: args }
    }

    if (Array.isArray(args)) {
        args = {
            date: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        date: args.date,
    }

    return show.definition.url
            .replace('{date}', parsedArgs.date.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ActivityController::show
* @see app/Http/Controllers/ActivityController.php:68
* @route '/activities/{date}'
*/
show.get = (args: { date: string | number } | [date: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ActivityController::show
* @see app/Http/Controllers/ActivityController.php:68
* @route '/activities/{date}'
*/
show.head = (args: { date: string | number } | [date: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

const activities = {
    show: Object.assign(show, show),
}

export default activities