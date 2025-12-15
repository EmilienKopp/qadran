import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\VerifyEmailController::__invoke
* @see app/Http/Controllers/Auth/VerifyEmailController.php:15
* @param account - Default: '$subdomain'
* @route '/{account?}/verify-email/{id}/{hash}'
*/
const VerifyEmailController = (args: { account?: string | number, id: string | number, hash: string | number } | [account: string | number, id: string | number, hash: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: VerifyEmailController.url(args, options),
    method: 'get',
})

VerifyEmailController.definition = {
    methods: ["get","head"],
    url: '/{account?}/verify-email/{id}/{hash}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\VerifyEmailController::__invoke
* @see app/Http/Controllers/Auth/VerifyEmailController.php:15
* @param account - Default: '$subdomain'
* @route '/{account?}/verify-email/{id}/{hash}'
*/
VerifyEmailController.url = (args: { account?: string | number, id: string | number, hash: string | number } | [account: string | number, id: string | number, hash: string | number ], options?: RouteQueryOptions) => {
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

    return VerifyEmailController.definition.url
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
VerifyEmailController.get = (args: { account?: string | number, id: string | number, hash: string | number } | [account: string | number, id: string | number, hash: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: VerifyEmailController.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\VerifyEmailController::__invoke
* @see app/Http/Controllers/Auth/VerifyEmailController.php:15
* @param account - Default: '$subdomain'
* @route '/{account?}/verify-email/{id}/{hash}'
*/
VerifyEmailController.head = (args: { account?: string | number, id: string | number, hash: string | number } | [account: string | number, id: string | number, hash: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: VerifyEmailController.url(args, options),
    method: 'head',
})

export default VerifyEmailController