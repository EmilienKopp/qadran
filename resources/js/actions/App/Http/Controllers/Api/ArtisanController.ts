import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\ArtisanController::run
* @see app/Http/Controllers/Api/ArtisanController.php:10
* @route '/api/artisan'
*/
export const run = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: run.url(options),
    method: 'get',
})

run.definition = {
    methods: ["get","head"],
    url: '/api/artisan',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ArtisanController::run
* @see app/Http/Controllers/Api/ArtisanController.php:10
* @route '/api/artisan'
*/
run.url = (options?: RouteQueryOptions) => {
    return run.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ArtisanController::run
* @see app/Http/Controllers/Api/ArtisanController.php:10
* @route '/api/artisan'
*/
run.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: run.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ArtisanController::run
* @see app/Http/Controllers/Api/ArtisanController.php:10
* @route '/api/artisan'
*/
run.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: run.url(options),
    method: 'head',
})

const ArtisanController = { run }

export default ArtisanController