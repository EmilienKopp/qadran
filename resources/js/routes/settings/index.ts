import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
/**
* @see routes/tenant.php:138
* @route '/settings/integrations'
*/
export const integrations = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: integrations.url(options),
    method: 'get',
})

integrations.definition = {
    methods: ["get","head"],
    url: '/settings/integrations',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/tenant.php:138
* @route '/settings/integrations'
*/
integrations.url = (options?: RouteQueryOptions) => {
    return integrations.definition.url + queryParams(options)
}

/**
* @see routes/tenant.php:138
* @route '/settings/integrations'
*/
integrations.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: integrations.url(options),
    method: 'get',
})

/**
* @see routes/tenant.php:138
* @route '/settings/integrations'
*/
integrations.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: integrations.url(options),
    method: 'head',
})

const settings = {
    integrations: Object.assign(integrations, integrations),
}

export default settings