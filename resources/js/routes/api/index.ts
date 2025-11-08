import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../wayfinder'
import webhooks from './webhooks'
/**
* @see \App\Http\Controllers\Api\ArtisanController::artisan
* @see app/Http/Controllers/Api/ArtisanController.php:10
* @route '/api/artisan'
*/
export const artisan = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: artisan.url(options),
    method: 'get',
})

artisan.definition = {
    methods: ["get","head"],
    url: '/api/artisan',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ArtisanController::artisan
* @see app/Http/Controllers/Api/ArtisanController.php:10
* @route '/api/artisan'
*/
artisan.url = (options?: RouteQueryOptions) => {
    return artisan.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ArtisanController::artisan
* @see app/Http/Controllers/Api/ArtisanController.php:10
* @route '/api/artisan'
*/
artisan.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: artisan.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ArtisanController::artisan
* @see app/Http/Controllers/Api/ArtisanController.php:10
* @route '/api/artisan'
*/
artisan.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: artisan.url(options),
    method: 'head',
})

/**
* @see routes/api.php:21
* @route '/api/instance-url/{host}'
*/
export const instanceUrl = (args: { host: string | number } | [host: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: instanceUrl.url(args, options),
    method: 'get',
})

instanceUrl.definition = {
    methods: ["get","head"],
    url: '/api/instance-url/{host}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/api.php:21
* @route '/api/instance-url/{host}'
*/
instanceUrl.url = (args: { host: string | number } | [host: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { host: args }
    }

    if (Array.isArray(args)) {
        args = {
            host: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        host: args.host,
    }

    return instanceUrl.definition.url
            .replace('{host}', parsedArgs.host.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see routes/api.php:21
* @route '/api/instance-url/{host}'
*/
instanceUrl.get = (args: { host: string | number } | [host: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: instanceUrl.url(args, options),
    method: 'get',
})

/**
* @see routes/api.php:21
* @route '/api/instance-url/{host}'
*/
instanceUrl.head = (args: { host: string | number } | [host: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: instanceUrl.url(args, options),
    method: 'head',
})

const api = {
    artisan: Object.assign(artisan, artisan),
    webhooks: Object.assign(webhooks, webhooks),
    instanceUrl: Object.assign(instanceUrl, instanceUrl),
}

export default api