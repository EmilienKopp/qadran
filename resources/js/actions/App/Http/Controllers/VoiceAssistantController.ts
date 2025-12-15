import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../../../wayfinder'
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
* @see \App\Http\Controllers\VoiceAssistantController::updatePreferences
* @see app/Http/Controllers/VoiceAssistantController.php:307
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/preferences'
*/
export const updatePreferences = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updatePreferences.url(args, options),
    method: 'patch',
})

updatePreferences.definition = {
    methods: ["patch"],
    url: '/{account?}/voice-assistant/preferences',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\VoiceAssistantController::updatePreferences
* @see app/Http/Controllers/VoiceAssistantController.php:307
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/preferences'
*/
updatePreferences.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return updatePreferences.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VoiceAssistantController::updatePreferences
* @see app/Http/Controllers/VoiceAssistantController.php:307
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/preferences'
*/
updatePreferences.patch = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updatePreferences.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\VoiceAssistantController::transcribeToAssistant
* @see app/Http/Controllers/VoiceAssistantController.php:351
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/transcribe'
*/
const transcribeToAssistantdb464d0660a99f554cf7b82d00215781 = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: transcribeToAssistantdb464d0660a99f554cf7b82d00215781.url(args, options),
    method: 'post',
})

transcribeToAssistantdb464d0660a99f554cf7b82d00215781.definition = {
    methods: ["post"],
    url: '/{account?}/voice-assistant/transcribe',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\VoiceAssistantController::transcribeToAssistant
* @see app/Http/Controllers/VoiceAssistantController.php:351
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/transcribe'
*/
transcribeToAssistantdb464d0660a99f554cf7b82d00215781.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return transcribeToAssistantdb464d0660a99f554cf7b82d00215781.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VoiceAssistantController::transcribeToAssistant
* @see app/Http/Controllers/VoiceAssistantController.php:351
* @param account - Default: '$subdomain'
* @route '/{account?}/voice-assistant/transcribe'
*/
transcribeToAssistantdb464d0660a99f554cf7b82d00215781.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: transcribeToAssistantdb464d0660a99f554cf7b82d00215781.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\VoiceAssistantController::transcribeToAssistant
* @see app/Http/Controllers/VoiceAssistantController.php:351
* @param account - Default: '$subdomain'
* @route '/{account?}/audio/assistant'
*/
const transcribeToAssistant778f977588a086c0ce79b9d65d4ab5f0 = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: transcribeToAssistant778f977588a086c0ce79b9d65d4ab5f0.url(args, options),
    method: 'post',
})

transcribeToAssistant778f977588a086c0ce79b9d65d4ab5f0.definition = {
    methods: ["post"],
    url: '/{account?}/audio/assistant',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\VoiceAssistantController::transcribeToAssistant
* @see app/Http/Controllers/VoiceAssistantController.php:351
* @param account - Default: '$subdomain'
* @route '/{account?}/audio/assistant'
*/
transcribeToAssistant778f977588a086c0ce79b9d65d4ab5f0.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return transcribeToAssistant778f977588a086c0ce79b9d65d4ab5f0.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VoiceAssistantController::transcribeToAssistant
* @see app/Http/Controllers/VoiceAssistantController.php:351
* @param account - Default: '$subdomain'
* @route '/{account?}/audio/assistant'
*/
transcribeToAssistant778f977588a086c0ce79b9d65d4ab5f0.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: transcribeToAssistant778f977588a086c0ce79b9d65d4ab5f0.url(args, options),
    method: 'post',
})

export const transcribeToAssistant = {
    '/{account?}/voice-assistant/transcribe': transcribeToAssistantdb464d0660a99f554cf7b82d00215781,
    '/{account?}/audio/assistant': transcribeToAssistant778f977588a086c0ce79b9d65d4ab5f0,
}

const VoiceAssistantController = { show, activate, deactivate, updatePreferences, transcribeToAssistant }

export default VoiceAssistantController