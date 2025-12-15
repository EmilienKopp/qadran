import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\PasswordController::update
* @see app/Http/Controllers/Auth/PasswordController.php:16
* @param account - Default: '$subdomain'
* @route '/{account?}/password'
*/
export const update = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/{account?}/password',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Auth\PasswordController::update
* @see app/Http/Controllers/Auth/PasswordController.php:16
* @param account - Default: '$subdomain'
* @route '/{account?}/password'
*/
update.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\PasswordController::update
* @see app/Http/Controllers/Auth/PasswordController.php:16
* @param account - Default: '$subdomain'
* @route '/{account?}/password'
*/
update.put = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

const PasswordController = { update }

export default PasswordController