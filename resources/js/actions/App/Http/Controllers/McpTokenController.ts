import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\McpTokenController::index
* @see app/Http/Controllers/McpTokenController.php:15
* @param account - Default: '$subdomain'
* @route '/{account?}/mcp-tokens'
*/
export const index = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/{account?}/mcp-tokens',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\McpTokenController::index
* @see app/Http/Controllers/McpTokenController.php:15
* @param account - Default: '$subdomain'
* @route '/{account?}/mcp-tokens'
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
* @see \App\Http\Controllers\McpTokenController::index
* @see app/Http/Controllers/McpTokenController.php:15
* @param account - Default: '$subdomain'
* @route '/{account?}/mcp-tokens'
*/
index.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\McpTokenController::index
* @see app/Http/Controllers/McpTokenController.php:15
* @param account - Default: '$subdomain'
* @route '/{account?}/mcp-tokens'
*/
index.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\McpTokenController::store
* @see app/Http/Controllers/McpTokenController.php:38
* @param account - Default: '$subdomain'
* @route '/{account?}/mcp-tokens'
*/
export const store = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/{account?}/mcp-tokens',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\McpTokenController::store
* @see app/Http/Controllers/McpTokenController.php:38
* @param account - Default: '$subdomain'
* @route '/{account?}/mcp-tokens'
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
* @see \App\Http\Controllers\McpTokenController::store
* @see app/Http/Controllers/McpTokenController.php:38
* @param account - Default: '$subdomain'
* @route '/{account?}/mcp-tokens'
*/
store.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\McpTokenController::destroy
* @see app/Http/Controllers/McpTokenController.php:67
* @param account - Default: '$subdomain'
* @route '/{account?}/mcp-tokens/{tokenId}'
*/
export const destroy = (args: { account?: string | number, tokenId: string | number } | [account: string | number, tokenId: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/{account?}/mcp-tokens/{tokenId}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\McpTokenController::destroy
* @see app/Http/Controllers/McpTokenController.php:67
* @param account - Default: '$subdomain'
* @route '/{account?}/mcp-tokens/{tokenId}'
*/
destroy.url = (args: { account?: string | number, tokenId: string | number } | [account: string | number, tokenId: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            tokenId: args[1],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        tokenId: args.tokenId,
    }

    return destroy.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{tokenId}', parsedArgs.tokenId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\McpTokenController::destroy
* @see app/Http/Controllers/McpTokenController.php:67
* @param account - Default: '$subdomain'
* @route '/{account?}/mcp-tokens/{tokenId}'
*/
destroy.delete = (args: { account?: string | number, tokenId: string | number } | [account: string | number, tokenId: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\McpTokenController::connectionInfo
* @see app/Http/Controllers/McpTokenController.php:102
* @param account - Default: '$subdomain'
* @route '/{account?}/mcp-tokens/connection-info'
*/
export const connectionInfo = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: connectionInfo.url(args, options),
    method: 'get',
})

connectionInfo.definition = {
    methods: ["get","head"],
    url: '/{account?}/mcp-tokens/connection-info',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\McpTokenController::connectionInfo
* @see app/Http/Controllers/McpTokenController.php:102
* @param account - Default: '$subdomain'
* @route '/{account?}/mcp-tokens/connection-info'
*/
connectionInfo.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return connectionInfo.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\McpTokenController::connectionInfo
* @see app/Http/Controllers/McpTokenController.php:102
* @param account - Default: '$subdomain'
* @route '/{account?}/mcp-tokens/connection-info'
*/
connectionInfo.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: connectionInfo.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\McpTokenController::connectionInfo
* @see app/Http/Controllers/McpTokenController.php:102
* @param account - Default: '$subdomain'
* @route '/{account?}/mcp-tokens/connection-info'
*/
connectionInfo.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: connectionInfo.url(args, options),
    method: 'head',
})

const McpTokenController = { index, store, destroy, connectionInfo }

export default McpTokenController