import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ProfileController::edit
* @see app/Http/Controllers/ProfileController.php:20
* @param account - Default: '$subdomain'
* @route '/{account?}/profile'
*/
export const edit = (args?: { account?: string | { id: string } } | [account: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/{account?}/profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProfileController::edit
* @see app/Http/Controllers/ProfileController.php:20
* @param account - Default: '$subdomain'
* @route '/{account?}/profile'
*/
edit.url = (args?: { account?: string | { id: string } } | [account: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { account: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { account: args.id }
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
        account: (typeof args?.account === 'object'
        ? args.account.id
        : args?.account) ?? '$subdomain',
    }

    return edit.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProfileController::edit
* @see app/Http/Controllers/ProfileController.php:20
* @param account - Default: '$subdomain'
* @route '/{account?}/profile'
*/
edit.get = (args?: { account?: string | { id: string } } | [account: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProfileController::edit
* @see app/Http/Controllers/ProfileController.php:20
* @param account - Default: '$subdomain'
* @route '/{account?}/profile'
*/
edit.head = (args?: { account?: string | { id: string } } | [account: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProfileController::update
* @see app/Http/Controllers/ProfileController.php:38
* @param account - Default: '$subdomain'
* @route '/{account?}/profile'
*/
export const update = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

update.definition = {
    methods: ["patch"],
    url: '/{account?}/profile',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\ProfileController::update
* @see app/Http/Controllers/ProfileController.php:38
* @param account - Default: '$subdomain'
* @route '/{account?}/profile'
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
* @see app/Http/Controllers/ProfileController.php:38
* @param account - Default: '$subdomain'
* @route '/{account?}/profile'
*/
update.patch = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\ProfileController::destroy
* @see app/Http/Controllers/ProfileController.php:74
* @param account - Default: '$subdomain'
* @route '/{account?}/profile'
*/
export const destroy = (args?: { account?: string | { id: string } } | [account: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/{account?}/profile',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ProfileController::destroy
* @see app/Http/Controllers/ProfileController.php:74
* @param account - Default: '$subdomain'
* @route '/{account?}/profile'
*/
destroy.url = (args?: { account?: string | { id: string } } | [account: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { account: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { account: args.id }
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
        account: (typeof args?.account === 'object'
        ? args.account.id
        : args?.account) ?? '$subdomain',
    }

    return destroy.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProfileController::destroy
* @see app/Http/Controllers/ProfileController.php:74
* @param account - Default: '$subdomain'
* @route '/{account?}/profile'
*/
destroy.delete = (args?: { account?: string | { id: string } } | [account: string | { id: string } ] | string | { id: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\ProfileController::updateTimezone
* @see app/Http/Controllers/ProfileController.php:51
* @param account - Default: '$subdomain'
* @route '/{account?}/profile/timezone'
*/
export const updateTimezone = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updateTimezone.url(args, options),
    method: 'post',
})

updateTimezone.definition = {
    methods: ["post"],
    url: '/{account?}/profile/timezone',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ProfileController::updateTimezone
* @see app/Http/Controllers/ProfileController.php:51
* @param account - Default: '$subdomain'
* @route '/{account?}/profile/timezone'
*/
updateTimezone.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return updateTimezone.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProfileController::updateTimezone
* @see app/Http/Controllers/ProfileController.php:51
* @param account - Default: '$subdomain'
* @route '/{account?}/profile/timezone'
*/
updateTimezone.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updateTimezone.url(args, options),
    method: 'post',
})

const ProfileController = { edit, update, destroy, updateTimezone }

export default ProfileController