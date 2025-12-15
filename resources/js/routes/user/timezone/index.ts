import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\ProfileController::update
* @see app/Http/Controllers/ProfileController.php:51
* @param account - Default: '$subdomain'
* @route '/{account?}/profile/timezone'
*/
export const update = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(args, options),
    method: 'post',
})

update.definition = {
    methods: ["post"],
    url: '/{account?}/profile/timezone',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ProfileController::update
* @see app/Http/Controllers/ProfileController.php:51
* @param account - Default: '$subdomain'
* @route '/{account?}/profile/timezone'
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
* @see \App\Http\Controllers\ProfileController::update
* @see app/Http/Controllers/ProfileController.php:51
* @param account - Default: '$subdomain'
* @route '/{account?}/profile/timezone'
*/
update.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(args, options),
    method: 'post',
})

const timezone = {
    update: Object.assign(update, update),
}

export default timezone