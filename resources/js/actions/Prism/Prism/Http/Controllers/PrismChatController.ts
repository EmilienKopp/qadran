import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \Prism\Prism\Http\Controllers\PrismChatController::__invoke
* @see vendor/prism-php/prism/src/Http/Controllers/PrismChatController.php:18
* @route '/prism/openai/v1/chat/completions'
*/
const PrismChatController = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: PrismChatController.url(options),
    method: 'post',
})

PrismChatController.definition = {
    methods: ["post"],
    url: '/prism/openai/v1/chat/completions',
} satisfies RouteDefinition<["post"]>

/**
* @see \Prism\Prism\Http\Controllers\PrismChatController::__invoke
* @see vendor/prism-php/prism/src/Http/Controllers/PrismChatController.php:18
* @route '/prism/openai/v1/chat/completions'
*/
PrismChatController.url = (options?: RouteQueryOptions) => {
    return PrismChatController.definition.url + queryParams(options)
}

/**
* @see \Prism\Prism\Http\Controllers\PrismChatController::__invoke
* @see vendor/prism-php/prism/src/Http/Controllers/PrismChatController.php:18
* @route '/prism/openai/v1/chat/completions'
*/
PrismChatController.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: PrismChatController.url(options),
    method: 'post',
})

export default PrismChatController