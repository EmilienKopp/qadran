import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\AudioController::transcribe
* @see app/Http/Controllers/AudioController.php:82
* @route '/audio/transcribe'
*/
export const transcribe = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: transcribe.url(options),
    method: 'post',
})

transcribe.definition = {
    methods: ["post"],
    url: '/audio/transcribe',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AudioController::transcribe
* @see app/Http/Controllers/AudioController.php:82
* @route '/audio/transcribe'
*/
transcribe.url = (options?: RouteQueryOptions) => {
    return transcribe.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AudioController::transcribe
* @see app/Http/Controllers/AudioController.php:82
* @route '/audio/transcribe'
*/
transcribe.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: transcribe.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AudioController::command
* @see app/Http/Controllers/AudioController.php:21
* @route '/audio/command'
*/
export const command = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: command.url(options),
    method: 'post',
})

command.definition = {
    methods: ["post"],
    url: '/audio/command',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AudioController::command
* @see app/Http/Controllers/AudioController.php:21
* @route '/audio/command'
*/
command.url = (options?: RouteQueryOptions) => {
    return command.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AudioController::command
* @see app/Http/Controllers/AudioController.php:21
* @route '/audio/command'
*/
command.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: command.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AudioController::assistant
* @see app/Http/Controllers/AudioController.php:158
* @route '/audio/assistant'
*/
export const assistant = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: assistant.url(options),
    method: 'post',
})

assistant.definition = {
    methods: ["post"],
    url: '/audio/assistant',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AudioController::assistant
* @see app/Http/Controllers/AudioController.php:158
* @route '/audio/assistant'
*/
assistant.url = (options?: RouteQueryOptions) => {
    return assistant.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AudioController::assistant
* @see app/Http/Controllers/AudioController.php:158
* @route '/audio/assistant'
*/
assistant.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: assistant.url(options),
    method: 'post',
})

const audio = {
    transcribe: Object.assign(transcribe, transcribe),
    command: Object.assign(command, command),
    assistant: Object.assign(assistant, assistant),
}

export default audio