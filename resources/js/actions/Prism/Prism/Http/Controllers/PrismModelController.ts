import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../../wayfinder'
/**
* @see \Prism\Prism\Http\Controllers\PrismModelController::__invoke
* @see vendor/prism-php/prism/src/Http/Controllers/PrismModelController.php:12
* @route '/prism/openai/v1/models'
*/
const PrismModelController = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PrismModelController.url(options),
    method: 'get',
})

PrismModelController.definition = {
    methods: ["get","head"],
    url: '/prism/openai/v1/models',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Prism\Prism\Http\Controllers\PrismModelController::__invoke
* @see vendor/prism-php/prism/src/Http/Controllers/PrismModelController.php:12
* @route '/prism/openai/v1/models'
*/
PrismModelController.url = (options?: RouteQueryOptions) => {
    return PrismModelController.definition.url + queryParams(options)
}

/**
* @see \Prism\Prism\Http\Controllers\PrismModelController::__invoke
* @see vendor/prism-php/prism/src/Http/Controllers/PrismModelController.php:12
* @route '/prism/openai/v1/models'
*/
PrismModelController.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: PrismModelController.url(options),
    method: 'get',
})

/**
* @see \Prism\Prism\Http\Controllers\PrismModelController::__invoke
* @see vendor/prism-php/prism/src/Http/Controllers/PrismModelController.php:12
* @route '/prism/openai/v1/models'
*/
PrismModelController.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: PrismModelController.url(options),
    method: 'head',
})

export default PrismModelController