import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\EmailVerificationNotificationController::store
* @see app/Http/Controllers/Auth/EmailVerificationNotificationController.php:14
* @param account - Default: '$subdomain'
* @route '/{account?}/email/verification-notification'
*/
export const store = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/{account?}/email/verification-notification',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\EmailVerificationNotificationController::store
* @see app/Http/Controllers/Auth/EmailVerificationNotificationController.php:14
* @param account - Default: '$subdomain'
* @route '/{account?}/email/verification-notification'
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
* @see \App\Http\Controllers\Auth\EmailVerificationNotificationController::store
* @see app/Http/Controllers/Auth/EmailVerificationNotificationController.php:14
* @param account - Default: '$subdomain'
* @route '/{account?}/email/verification-notification'
*/
store.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

const EmailVerificationNotificationController = { store }

export default EmailVerificationNotificationController