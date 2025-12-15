import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::store
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:46
* @route '/space-selection'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/space-selection',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::store
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:46
* @route '/space-selection'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\AuthenticatedSessionController::store
* @see app/Http/Controllers/Auth/AuthenticatedSessionController.php:46
* @route '/space-selection'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

const spaceSelection = {
    store: Object.assign(store, store),
}

export default spaceSelection