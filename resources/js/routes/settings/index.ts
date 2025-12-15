import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../wayfinder'
/**
* @see routes/tenant.php:197
* @param account - Default: '$subdomain'
* @route '/{account?}/settings/integrations'
*/
export const integrations = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: integrations.url(args, options),
    method: 'get',
})

integrations.definition = {
    methods: ["get","head"],
    url: '/{account?}/settings/integrations',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/tenant.php:197
* @param account - Default: '$subdomain'
* @route '/{account?}/settings/integrations'
*/
integrations.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return integrations.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see routes/tenant.php:197
* @param account - Default: '$subdomain'
* @route '/{account?}/settings/integrations'
*/
integrations.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: integrations.url(args, options),
    method: 'get',
})

/**
* @see routes/tenant.php:197
* @param account - Default: '$subdomain'
* @route '/{account?}/settings/integrations'
*/
integrations.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: integrations.url(args, options),
    method: 'head',
})

const settings = {
    integrations: Object.assign(integrations, integrations),
}

export default settings