import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\VoiceAssistantController::show
* @see app/Http/Controllers/VoiceAssistantController.php:23
* @route '/voice-assistant'
*/
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/voice-assistant',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\VoiceAssistantController::show
* @see app/Http/Controllers/VoiceAssistantController.php:23
* @route '/voice-assistant'
*/
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\VoiceAssistantController::show
* @see app/Http/Controllers/VoiceAssistantController.php:23
* @route '/voice-assistant'
*/
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VoiceAssistantController::show
* @see app/Http/Controllers/VoiceAssistantController.php:23
* @route '/voice-assistant'
*/
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\VoiceAssistantController::activate
* @see app/Http/Controllers/VoiceAssistantController.php:45
* @route '/voice-assistant/activate'
*/
export const activate = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: activate.url(options),
    method: 'post',
})

activate.definition = {
    methods: ["post"],
    url: '/voice-assistant/activate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\VoiceAssistantController::activate
* @see app/Http/Controllers/VoiceAssistantController.php:45
* @route '/voice-assistant/activate'
*/
activate.url = (options?: RouteQueryOptions) => {
    return activate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\VoiceAssistantController::activate
* @see app/Http/Controllers/VoiceAssistantController.php:45
* @route '/voice-assistant/activate'
*/
activate.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: activate.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\VoiceAssistantController::deactivate
* @see app/Http/Controllers/VoiceAssistantController.php:201
* @route '/voice-assistant/deactivate'
*/
export const deactivate = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: deactivate.url(options),
    method: 'post',
})

deactivate.definition = {
    methods: ["post"],
    url: '/voice-assistant/deactivate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\VoiceAssistantController::deactivate
* @see app/Http/Controllers/VoiceAssistantController.php:201
* @route '/voice-assistant/deactivate'
*/
deactivate.url = (options?: RouteQueryOptions) => {
    return deactivate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\VoiceAssistantController::deactivate
* @see app/Http/Controllers/VoiceAssistantController.php:201
* @route '/voice-assistant/deactivate'
*/
deactivate.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: deactivate.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\VoiceAssistantController::preferences
* @see app/Http/Controllers/VoiceAssistantController.php:271
* @route '/voice-assistant/preferences'
*/
export const preferences = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: preferences.url(options),
    method: 'patch',
})

preferences.definition = {
    methods: ["patch"],
    url: '/voice-assistant/preferences',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\VoiceAssistantController::preferences
* @see app/Http/Controllers/VoiceAssistantController.php:271
* @route '/voice-assistant/preferences'
*/
preferences.url = (options?: RouteQueryOptions) => {
    return preferences.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\VoiceAssistantController::preferences
* @see app/Http/Controllers/VoiceAssistantController.php:271
* @route '/voice-assistant/preferences'
*/
preferences.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: preferences.url(options),
    method: 'patch',
})

const voiceAssistant = {
    show: Object.assign(show, show),
    activate: Object.assign(activate, activate),
    deactivate: Object.assign(deactivate, deactivate),
    preferences: Object.assign(preferences, preferences),
}

export default voiceAssistant