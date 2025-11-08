import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\McpTokenController::index
* @see app/Http/Controllers/McpTokenController.php:17
* @route '/mcp-tokens'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/mcp-tokens',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\McpTokenController::index
* @see app/Http/Controllers/McpTokenController.php:17
* @route '/mcp-tokens'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\McpTokenController::index
* @see app/Http/Controllers/McpTokenController.php:17
* @route '/mcp-tokens'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\McpTokenController::index
* @see app/Http/Controllers/McpTokenController.php:17
* @route '/mcp-tokens'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\McpTokenController::store
* @see app/Http/Controllers/McpTokenController.php:40
* @route '/mcp-tokens'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/mcp-tokens',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\McpTokenController::store
* @see app/Http/Controllers/McpTokenController.php:40
* @route '/mcp-tokens'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\McpTokenController::store
* @see app/Http/Controllers/McpTokenController.php:40
* @route '/mcp-tokens'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\McpTokenController::destroy
* @see app/Http/Controllers/McpTokenController.php:80
* @route '/mcp-tokens/{tokenId}'
*/
export const destroy = (args: { tokenId: string | number } | [tokenId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/mcp-tokens/{tokenId}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\McpTokenController::destroy
* @see app/Http/Controllers/McpTokenController.php:80
* @route '/mcp-tokens/{tokenId}'
*/
destroy.url = (args: { tokenId: string | number } | [tokenId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tokenId: args }
    }

    if (Array.isArray(args)) {
        args = {
            tokenId: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        tokenId: args.tokenId,
    }

    return destroy.definition.url
            .replace('{tokenId}', parsedArgs.tokenId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\McpTokenController::destroy
* @see app/Http/Controllers/McpTokenController.php:80
* @route '/mcp-tokens/{tokenId}'
*/
destroy.delete = (args: { tokenId: string | number } | [tokenId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\McpTokenController::connectionInfo
* @see app/Http/Controllers/McpTokenController.php:115
* @route '/mcp-tokens/connection-info'
*/
export const connectionInfo = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: connectionInfo.url(options),
    method: 'get',
})

connectionInfo.definition = {
    methods: ["get","head"],
    url: '/mcp-tokens/connection-info',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\McpTokenController::connectionInfo
* @see app/Http/Controllers/McpTokenController.php:115
* @route '/mcp-tokens/connection-info'
*/
connectionInfo.url = (options?: RouteQueryOptions) => {
    return connectionInfo.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\McpTokenController::connectionInfo
* @see app/Http/Controllers/McpTokenController.php:115
* @route '/mcp-tokens/connection-info'
*/
connectionInfo.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: connectionInfo.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\McpTokenController::connectionInfo
* @see app/Http/Controllers/McpTokenController.php:115
* @route '/mcp-tokens/connection-info'
*/
connectionInfo.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: connectionInfo.url(options),
    method: 'head',
})

const McpTokenController = { index, store, destroy, connectionInfo }

export default McpTokenController