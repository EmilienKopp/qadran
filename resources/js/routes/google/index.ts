import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\GoogleOAuthController::login
* @see app/Http/Controllers/GoogleOAuthController.php:26
* @route '/google/login'
*/
export const login = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: login.url(options),
    method: 'get',
})

login.definition = {
    methods: ["get","head"],
    url: '/google/login',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\GoogleOAuthController::login
* @see app/Http/Controllers/GoogleOAuthController.php:26
* @route '/google/login'
*/
login.url = (options?: RouteQueryOptions) => {
    return login.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\GoogleOAuthController::login
* @see app/Http/Controllers/GoogleOAuthController.php:26
* @route '/google/login'
*/
login.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: login.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GoogleOAuthController::login
* @see app/Http/Controllers/GoogleOAuthController.php:26
* @route '/google/login'
*/
login.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: login.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\GoogleOAuthController::callback
* @see app/Http/Controllers/GoogleOAuthController.php:36
* @route '/auth/google/callback'
*/
export const callback = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: callback.url(options),
    method: 'get',
})

callback.definition = {
    methods: ["get","head"],
    url: '/auth/google/callback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\GoogleOAuthController::callback
* @see app/Http/Controllers/GoogleOAuthController.php:36
* @route '/auth/google/callback'
*/
callback.url = (options?: RouteQueryOptions) => {
    return callback.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\GoogleOAuthController::callback
* @see app/Http/Controllers/GoogleOAuthController.php:36
* @route '/auth/google/callback'
*/
callback.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: callback.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\GoogleOAuthController::callback
* @see app/Http/Controllers/GoogleOAuthController.php:36
* @route '/auth/google/callback'
*/
callback.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: callback.url(options),
    method: 'head',
})

const google = {
    login: Object.assign(login, login),
    callback: Object.assign(callback, callback),
}

export default google