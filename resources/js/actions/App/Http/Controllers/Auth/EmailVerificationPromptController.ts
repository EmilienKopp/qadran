import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\EmailVerificationPromptController::__invoke
* @see app/Http/Controllers/Auth/EmailVerificationPromptController.php:16
* @param account - Default: '$subdomain'
* @route '/{account?}/verify-email'
*/
const EmailVerificationPromptController = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EmailVerificationPromptController.url(args, options),
    method: 'get',
})

EmailVerificationPromptController.definition = {
    methods: ["get","head"],
    url: '/{account?}/verify-email',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\EmailVerificationPromptController::__invoke
* @see app/Http/Controllers/Auth/EmailVerificationPromptController.php:16
* @param account - Default: '$subdomain'
* @route '/{account?}/verify-email'
*/
EmailVerificationPromptController.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return EmailVerificationPromptController.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\EmailVerificationPromptController::__invoke
* @see app/Http/Controllers/Auth/EmailVerificationPromptController.php:16
* @param account - Default: '$subdomain'
* @route '/{account?}/verify-email'
*/
EmailVerificationPromptController.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: EmailVerificationPromptController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\EmailVerificationPromptController::__invoke
* @see app/Http/Controllers/Auth/EmailVerificationPromptController.php:16
* @param account - Default: '$subdomain'
* @route '/{account?}/verify-email'
*/
EmailVerificationPromptController.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: EmailVerificationPromptController.url(args, options),
    method: 'head',
})

export default EmailVerificationPromptController