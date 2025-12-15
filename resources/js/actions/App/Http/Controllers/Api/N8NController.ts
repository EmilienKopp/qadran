import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\N8NController::decryptTenant
* @see app/Http/Controllers/Api/N8NController.php:12
* @route '/api/n8n/decrypt-tenant'
*/
export const decryptTenant = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: decryptTenant.url(options),
    method: 'post',
})

decryptTenant.definition = {
    methods: ["post"],
    url: '/api/n8n/decrypt-tenant',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Api\N8NController::decryptTenant
* @see app/Http/Controllers/Api/N8NController.php:12
* @route '/api/n8n/decrypt-tenant'
*/
decryptTenant.url = (options?: RouteQueryOptions) => {
    return decryptTenant.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\N8NController::decryptTenant
* @see app/Http/Controllers/Api/N8NController.php:12
* @route '/api/n8n/decrypt-tenant'
*/
decryptTenant.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: decryptTenant.url(options),
    method: 'post',
})

const N8NController = { decryptTenant }

export default N8NController