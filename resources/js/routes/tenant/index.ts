import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../wayfinder'
/**
* @see routes/tenant.php:26
* @param account - Default: '$subdomain'
* @route '/{account?}'
*/
export const root = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: root.url(args, options),
    method: 'get',
})

root.definition = {
    methods: ["get","head"],
    url: '/{account?}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/tenant.php:26
* @param account - Default: '$subdomain'
* @route '/{account?}'
*/
root.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return root.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see routes/tenant.php:26
* @param account - Default: '$subdomain'
* @route '/{account?}'
*/
root.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: root.url(args, options),
    method: 'get',
})

/**
* @see routes/tenant.php:26
* @param account - Default: '$subdomain'
* @route '/{account?}'
*/
root.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: root.url(args, options),
    method: 'head',
})

/**
* @see routes/tenant.php:37
* @param account - Default: '$subdomain'
* @route '/{account?}/landing'
*/
export const landing = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: landing.url(args, options),
    method: 'get',
})

landing.definition = {
    methods: ["get","head"],
    url: '/{account?}/landing',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/tenant.php:37
* @param account - Default: '$subdomain'
* @route '/{account?}/landing'
*/
landing.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return landing.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see routes/tenant.php:37
* @param account - Default: '$subdomain'
* @route '/{account?}/landing'
*/
landing.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: landing.url(args, options),
    method: 'get',
})

/**
* @see routes/tenant.php:37
* @param account - Default: '$subdomain'
* @route '/{account?}/landing'
*/
landing.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: landing.url(args, options),
    method: 'head',
})

/**
* @see routes/tenant.php:49
* @param account - Default: '$subdomain'
* @route '/{account?}/welcome'
*/
export const welcome = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: welcome.url(args, options),
    method: 'get',
})

welcome.definition = {
    methods: ["get","head"],
    url: '/{account?}/welcome',
} satisfies RouteDefinition<["get","head"]>

/**
* @see routes/tenant.php:49
* @param account - Default: '$subdomain'
* @route '/{account?}/welcome'
*/
welcome.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return welcome.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see routes/tenant.php:49
* @param account - Default: '$subdomain'
* @route '/{account?}/welcome'
*/
welcome.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: welcome.url(args, options),
    method: 'get',
})

/**
* @see routes/tenant.php:49
* @param account - Default: '$subdomain'
* @route '/{account?}/welcome'
*/
welcome.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: welcome.url(args, options),
    method: 'head',
})

const tenant = {
    root: Object.assign(root, root),
    landing: Object.assign(landing, landing),
    welcome: Object.assign(welcome, welcome),
}

export default tenant