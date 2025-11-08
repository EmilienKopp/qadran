import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\KnownIssuesController::index
* @see app/Http/Controllers/KnownIssuesController.php:13
* @route '/known-issues'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/known-issues',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\KnownIssuesController::index
* @see app/Http/Controllers/KnownIssuesController.php:13
* @route '/known-issues'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\KnownIssuesController::index
* @see app/Http/Controllers/KnownIssuesController.php:13
* @route '/known-issues'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\KnownIssuesController::index
* @see app/Http/Controllers/KnownIssuesController.php:13
* @route '/known-issues'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

const knownIssues = {
    index: Object.assign(index, index),
}

export default knownIssues