import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
import authorizations from './authorizations'
import token0f65b5 from './token'
/**
* @see \Laravel\Passport\Http\Controllers\AccessTokenController::token
* @see vendor/laravel/passport/src/Http/Controllers/AccessTokenController.php:25
* @route '/oauth/token'
*/
export const token = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: token.url(options),
    method: 'post',
})

token.definition = {
    methods: ["post"],
    url: '/oauth/token',
} satisfies RouteDefinition<["post"]>

/**
* @see \Laravel\Passport\Http\Controllers\AccessTokenController::token
* @see vendor/laravel/passport/src/Http/Controllers/AccessTokenController.php:25
* @route '/oauth/token'
*/
token.url = (options?: RouteQueryOptions) => {
    return token.definition.url + queryParams(options)
}

/**
* @see \Laravel\Passport\Http\Controllers\AccessTokenController::token
* @see vendor/laravel/passport/src/Http/Controllers/AccessTokenController.php:25
* @route '/oauth/token'
*/
token.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: token.url(options),
    method: 'post',
})

const passport = {
    authorizations: Object.assign(authorizations, authorizations),
    token: Object.assign(token, token0f65b5),
}

export default passport