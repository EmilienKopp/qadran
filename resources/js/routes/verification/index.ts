import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\EmailVerificationPromptController::__invoke
* @see app/Http/Controllers/Auth/EmailVerificationPromptController.php:16
* @param account - Default: '$subdomain'
* @route '/{account?}/verify-email'
*/
export const notice = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: notice.url(args, options),
    method: 'get',
})

notice.definition = {
    methods: ["get","head"],
    url: '/{account?}/verify-email',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\EmailVerificationPromptController::__invoke
* @see app/Http/Controllers/Auth/EmailVerificationPromptController.php:16
* @param account - Default: '$subdomain'
* @route '/{account?}/verify-email'
*/
notice.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return notice.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\EmailVerificationPromptController::__invoke
* @see app/Http/Controllers/Auth/EmailVerificationPromptController.php:16
* @param account - Default: '$subdomain'
* @route '/{account?}/verify-email'
*/
notice.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: notice.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\EmailVerificationPromptController::__invoke
* @see app/Http/Controllers/Auth/EmailVerificationPromptController.php:16
* @param account - Default: '$subdomain'
* @route '/{account?}/verify-email'
*/
notice.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: notice.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\VerifyEmailController::__invoke
* @see app/Http/Controllers/Auth/VerifyEmailController.php:15
* @param account - Default: '$subdomain'
* @route '/{account?}/verify-email/{id}/{hash}'
*/
export const verify = (args: { account?: string | number, id: string | number, hash: string | number } | [account: string | number, id: string | number, hash: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: verify.url(args, options),
    method: 'get',
})

verify.definition = {
    methods: ["get","head"],
    url: '/{account?}/verify-email/{id}/{hash}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\VerifyEmailController::__invoke
* @see app/Http/Controllers/Auth/VerifyEmailController.php:15
* @param account - Default: '$subdomain'
* @route '/{account?}/verify-email/{id}/{hash}'
*/
verify.url = (args: { account?: string | number, id: string | number, hash: string | number } | [account: string | number, id: string | number, hash: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            account: args[0],
            id: args[1],
            hash: args[2],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args.account ?? '$subdomain',
        id: args.id,
        hash: args.hash,
    }

    return verify.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace('{id}', parsedArgs.id.toString())
            .replace('{hash}', parsedArgs.hash.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\VerifyEmailController::__invoke
* @see app/Http/Controllers/Auth/VerifyEmailController.php:15
* @param account - Default: '$subdomain'
* @route '/{account?}/verify-email/{id}/{hash}'
*/
verify.get = (args: { account?: string | number, id: string | number, hash: string | number } | [account: string | number, id: string | number, hash: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: verify.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\VerifyEmailController::__invoke
* @see app/Http/Controllers/Auth/VerifyEmailController.php:15
* @param account - Default: '$subdomain'
* @route '/{account?}/verify-email/{id}/{hash}'
*/
verify.head = (args: { account?: string | number, id: string | number, hash: string | number } | [account: string | number, id: string | number, hash: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: verify.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\EmailVerificationNotificationController::send
* @see app/Http/Controllers/Auth/EmailVerificationNotificationController.php:14
* @param account - Default: '$subdomain'
* @route '/{account?}/email/verification-notification'
*/
export const send = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: send.url(args, options),
    method: 'post',
})

send.definition = {
    methods: ["post"],
    url: '/{account?}/email/verification-notification',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\EmailVerificationNotificationController::send
* @see app/Http/Controllers/Auth/EmailVerificationNotificationController.php:14
* @param account - Default: '$subdomain'
* @route '/{account?}/email/verification-notification'
*/
send.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return send.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\EmailVerificationNotificationController::send
* @see app/Http/Controllers/Auth/EmailVerificationNotificationController.php:14
* @param account - Default: '$subdomain'
* @route '/{account?}/email/verification-notification'
*/
send.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: send.url(args, options),
    method: 'post',
})

const verification = {
    notice: Object.assign(notice, notice),
    verify: Object.assign(verify, verify),
    send: Object.assign(send, send),
}

export default verification