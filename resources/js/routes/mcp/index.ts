import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
/**
* @see vendor/laravel/mcp/src/Server/Registrar.php:35
* @route '/mcp/qadran'
*/
export const qadran = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: qadran.url(options),
    method: 'post',
})

qadran.definition = {
    methods: ["post"],
    url: '/mcp/qadran',
} satisfies RouteDefinition<["post"]>

/**
* @see vendor/laravel/mcp/src/Server/Registrar.php:35
* @route '/mcp/qadran'
*/
qadran.url = (options?: RouteQueryOptions) => {
    return qadran.definition.url + queryParams(options)
}

/**
* @see vendor/laravel/mcp/src/Server/Registrar.php:35
* @route '/mcp/qadran'
*/
qadran.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: qadran.url(options),
    method: 'post',
})

const mcp = {
    qadran: Object.assign(qadran, qadran),
}

export default mcp