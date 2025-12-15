import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../wayfinder'
/**
* @see \App\Http\Controllers\VoiceAssistantController::show
* @see app/Http/Controllers/VoiceAssistantController.php:27
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant'
*/
export const show = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/{account?}/voice-assistant',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\VoiceAssistantController::show
* @see app/Http/Controllers/VoiceAssistantController.php:27
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant'
*/
show.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { account: args }
    }

    if (Array.isArray(args)) {
        args = {
            account: args[0],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args?.account ?? '$subdomain',
    }

    return show.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VoiceAssistantController::show
* @see app/Http/Controllers/VoiceAssistantController.php:27
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant'
*/
show.get = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VoiceAssistantController::show
* @see app/Http/Controllers/VoiceAssistantController.php:27
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant'
*/
show.head = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\VoiceAssistantController::activate
* @see app/Http/Controllers/VoiceAssistantController.php:48
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/activate'
*/
export const activate = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: activate.url(args, options),
    method: 'post',
})

activate.definition = {
    methods: ["post"],
    url: '/{account?}/voice-assistant/activate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\VoiceAssistantController::activate
* @see app/Http/Controllers/VoiceAssistantController.php:48
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/activate'
*/
activate.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { account: args }
    }

    if (Array.isArray(args)) {
        args = {
            account: args[0],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args?.account ?? '$subdomain',
    }

    return activate.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VoiceAssistantController::activate
* @see app/Http/Controllers/VoiceAssistantController.php:48
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/activate'
*/
activate.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: activate.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\VoiceAssistantController::deactivate
* @see app/Http/Controllers/VoiceAssistantController.php:238
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/deactivate'
*/
export const deactivate = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: deactivate.url(args, options),
    method: 'post',
})

deactivate.definition = {
    methods: ["post"],
    url: '/{account?}/voice-assistant/deactivate',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\VoiceAssistantController::deactivate
* @see app/Http/Controllers/VoiceAssistantController.php:238
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/deactivate'
*/
deactivate.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { account: args }
    }

    if (Array.isArray(args)) {
        args = {
            account: args[0],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args?.account ?? '$subdomain',
    }

    return deactivate.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VoiceAssistantController::deactivate
* @see app/Http/Controllers/VoiceAssistantController.php:238
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/deactivate'
*/
deactivate.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: deactivate.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\VoiceAssistantController::preferences
* @see app/Http/Controllers/VoiceAssistantController.php:307
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/preferences'
*/
export const preferences = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: preferences.url(args, options),
    method: 'patch',
})

preferences.definition = {
    methods: ["patch"],
    url: '/{account?}/voice-assistant/preferences',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\VoiceAssistantController::preferences
* @see app/Http/Controllers/VoiceAssistantController.php:307
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/preferences'
*/
preferences.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { account: args }
    }

    if (Array.isArray(args)) {
        args = {
            account: args[0],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args?.account ?? '$subdomain',
    }

    return preferences.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VoiceAssistantController::preferences
* @see app/Http/Controllers/VoiceAssistantController.php:307
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/preferences'
*/
preferences.patch = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: preferences.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\VoiceAssistantController::transcribe
* @see app/Http/Controllers/VoiceAssistantController.php:351
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/transcribe'
*/
export const transcribe = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: transcribe.url(args, options),
    method: 'post',
})

transcribe.definition = {
    methods: ["post"],
    url: '/{account?}/voice-assistant/transcribe',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\VoiceAssistantController::transcribe
* @see app/Http/Controllers/VoiceAssistantController.php:351
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/transcribe'
*/
transcribe.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { account: args }
    }

    if (Array.isArray(args)) {
        args = {
            account: args[0],
        }
    }

    args = applyUrlDefaults(args)

    validateParameters(args, [
        "account",
    ])

    const parsedArgs = {
        account: args?.account ?? '$subdomain',
    }

    return transcribe.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VoiceAssistantController::transcribe
* @see app/Http/Controllers/VoiceAssistantController.php:351
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/transcribe'
*/
transcribe.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: transcribe.url(args, options),
    method: 'post',
})

const voiceAssistant = {
    show: Object.assign(show, show),
    activate: Object.assign(activate, activate),
    deactivate: Object.assign(deactivate, deactivate),
    preferences: Object.assign(preferences, preferences),
    transcribe: Object.assign(transcribe, transcribe),
}

export default voiceAssistant