import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\PrivacyPolicyController::index
* @see app/Http/Controllers/PrivacyPolicyController.php:13
* @route '/privacy-policy'
*/
const index546d1d979582dcab4cda77f98be026ca = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index546d1d979582dcab4cda77f98be026ca.url(options),
    method: 'get',
})

index546d1d979582dcab4cda77f98be026ca.definition = {
    methods: ["get","head"],
    url: '/privacy-policy',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PrivacyPolicyController::index
* @see app/Http/Controllers/PrivacyPolicyController.php:13
* @route '/privacy-policy'
*/
index546d1d979582dcab4cda77f98be026ca.url = (options?: RouteQueryOptions) => {
    return index546d1d979582dcab4cda77f98be026ca.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrivacyPolicyController::index
* @see app/Http/Controllers/PrivacyPolicyController.php:13
* @route '/privacy-policy'
*/
index546d1d979582dcab4cda77f98be026ca.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index546d1d979582dcab4cda77f98be026ca.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PrivacyPolicyController::index
* @see app/Http/Controllers/PrivacyPolicyController.php:13
* @route '/privacy-policy'
*/
index546d1d979582dcab4cda77f98be026ca.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index546d1d979582dcab4cda77f98be026ca.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\PrivacyPolicyController::index
* @see app/Http/Controllers/PrivacyPolicyController.php:13
* @param account - Default: '$subdomain'
* @route '/{account?}/privacy-policy'
*/
const index4b3ce6176521acd626a81bfae6d613ea = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index4b3ce6176521acd626a81bfae6d613ea.url(args, options),
    method: 'get',
})

index4b3ce6176521acd626a81bfae6d613ea.definition = {
    methods: ["get","head"],
    url: '/{account?}/privacy-policy',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PrivacyPolicyController::index
* @see app/Http/Controllers/PrivacyPolicyController.php:13
* @param account - Default: '$subdomain'
* @route '/{account?}/privacy-policy'
*/
index4b3ce6176521acd626a81bfae6d613ea.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return index4b3ce6176521acd626a81bfae6d613ea.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PrivacyPolicyController::index
* @see app/Http/Controllers/PrivacyPolicyController.php:13
* @param account - Default: '$subdomain'
* @route '/{account?}/privacy-policy'
*/
index4b3ce6176521acd626a81bfae6d613ea.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index4b3ce6176521acd626a81bfae6d613ea.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PrivacyPolicyController::index
* @see app/Http/Controllers/PrivacyPolicyController.php:13
* @param account - Default: '$subdomain'
* @route '/{account?}/privacy-policy'
*/
index4b3ce6176521acd626a81bfae6d613ea.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index4b3ce6176521acd626a81bfae6d613ea.url(args, options),
    method: 'head',
})

export const index = {
    '/privacy-policy': index546d1d979582dcab4cda77f98be026ca,
    '/{account?}/privacy-policy': index4b3ce6176521acd626a81bfae6d613ea,
}

const PrivacyPolicyController = { index }

export default PrivacyPolicyController