import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../wayfinder'
/**
* @see \App\Http\Controllers\PrivacyPolicyController::index
* @see app/Http/Controllers/PrivacyPolicyController.php:13
* @route '/privacy-policy'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/privacy-policy',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PrivacyPolicyController::index
* @see app/Http/Controllers/PrivacyPolicyController.php:13
* @route '/privacy-policy'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrivacyPolicyController::index
* @see app/Http/Controllers/PrivacyPolicyController.php:13
* @route '/privacy-policy'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PrivacyPolicyController::index
* @see app/Http/Controllers/PrivacyPolicyController.php:13
* @route '/privacy-policy'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\PrivacyPolicyController::index
* @see app/Http/Controllers/PrivacyPolicyController.php:13
* @param account - Default: '$subdomain'
* @route '/{account?}/privacy-policy'
*/
export const index = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/{account?}/privacy-policy',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PrivacyPolicyController::index
* @see app/Http/Controllers/PrivacyPolicyController.php:13
* @param account - Default: '$subdomain'
* @route '/{account?}/privacy-policy'
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
* @see \App\Http\Controllers\PrivacyPolicyController::index
* @see app/Http/Controllers/PrivacyPolicyController.php:13
* @param account - Default: '$subdomain'
* @route '/{account?}/privacy-policy'
*/
index.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PrivacyPolicyController::index
* @see app/Http/Controllers/PrivacyPolicyController.php:13
* @param account - Default: '$subdomain'
* @route '/{account?}/privacy-policy'
*/
index.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

const privacyPolicy = {
    index: Object.assign(index, index),
}

export default privacyPolicy