import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\KnownIssueWebhookController::store
* @see app/Http/Controllers/KnownIssueWebhookController.php:15
* @route '/webhooks/jira/known-issues'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/webhooks/jira/known-issues',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\KnownIssueWebhookController::store
* @see app/Http/Controllers/KnownIssueWebhookController.php:15
* @route '/webhooks/jira/known-issues'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\KnownIssueWebhookController::store
* @see app/Http/Controllers/KnownIssueWebhookController.php:15
* @route '/webhooks/jira/known-issues'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

const KnownIssueWebhookController = { store }

export default KnownIssueWebhookController