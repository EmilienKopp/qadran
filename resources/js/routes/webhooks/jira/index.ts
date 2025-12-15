import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\KnownIssueWebhookController::knownIssues
* @see app/Http/Controllers/KnownIssueWebhookController.php:15
* @route '/webhooks/jira/known-issues'
*/
export const knownIssues = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: knownIssues.url(options),
    method: 'post',
})

knownIssues.definition = {
    methods: ["post"],
    url: '/webhooks/jira/known-issues',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\KnownIssueWebhookController::knownIssues
* @see app/Http/Controllers/KnownIssueWebhookController.php:15
* @route '/webhooks/jira/known-issues'
*/
knownIssues.url = (options?: RouteQueryOptions) => {
    return knownIssues.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\KnownIssueWebhookController::knownIssues
* @see app/Http/Controllers/KnownIssueWebhookController.php:15
* @route '/webhooks/jira/known-issues'
*/
knownIssues.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: knownIssues.url(options),
    method: 'post',
})

const jira = {
    knownIssues: Object.assign(knownIssues, knownIssues),
}

export default jira