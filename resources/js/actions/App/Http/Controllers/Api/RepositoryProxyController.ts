import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\RepositoryProxyController::call
* @see app/Http/Controllers/Api/RepositoryProxyController.php:14
* @route '/api/repository/call'
*/
export const call = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: call.url(options),
    method: 'post',
})

call.definition = {
    methods: ["post"],
    url: '/api/repository/call',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\RepositoryProxyController::call
* @see app/Http/Controllers/Api/RepositoryProxyController.php:14
* @route '/api/repository/call'
*/
call.url = (options?: RouteQueryOptions) => {
    return call.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\RepositoryProxyController::call
* @see app/Http/Controllers/Api/RepositoryProxyController.php:14
* @route '/api/repository/call'
*/
call.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: call.url(options),
    method: 'post',
})

const RepositoryProxyController = { call }

export default RepositoryProxyController