import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults, validateParameters } from './../../wayfinder'
/**
* @see \App\Http\Controllers\AudioController::transcribe
* @see app/Http/Controllers/AudioController.php:80
* @param account - Default: '$subdomain'
* @route '/{account?}/audio/transcribe'
*/
export const transcribe = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: transcribe.url(args, options),
    method: 'post',
})

transcribe.definition = {
    methods: ["post"],
    url: '/{account?}/audio/transcribe',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AudioController::transcribe
* @see app/Http/Controllers/AudioController.php:80
* @param account - Default: '$subdomain'
* @route '/{account?}/audio/transcribe'
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
* @see \App\Http\Controllers\AudioController::transcribe
* @see app/Http/Controllers/AudioController.php:80
* @param account - Default: '$subdomain'
* @route '/{account?}/audio/transcribe'
*/
transcribe.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: transcribe.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AudioController::command
* @see app/Http/Controllers/AudioController.php:18
* @param account - Default: '$subdomain'
* @route '/{account?}/audio/command'
*/
export const command = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: command.url(args, options),
    method: 'post',
})

command.definition = {
    methods: ["post"],
    url: '/{account?}/audio/command',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AudioController::command
* @see app/Http/Controllers/AudioController.php:18
* @param account - Default: '$subdomain'
* @route '/{account?}/audio/command'
*/
command.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return command.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AudioController::command
* @see app/Http/Controllers/AudioController.php:18
* @param account - Default: '$subdomain'
* @route '/{account?}/audio/command'
*/
command.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: command.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\VoiceAssistantController::assistant
* @see app/Http/Controllers/VoiceAssistantController.php:351
* @param account - Default: '$subdomain'
* @route '/{account?}/audio/assistant'
*/
export const assistant = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: assistant.url(args, options),
    method: 'post',
})

assistant.definition = {
    methods: ["post"],
    url: '/{account?}/audio/assistant',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\VoiceAssistantController::assistant
* @see app/Http/Controllers/VoiceAssistantController.php:351
* @param account - Default: '$subdomain'
* @route '/{account?}/audio/assistant'
*/
assistant.url = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return assistant.definition.url
            .replace('{account?}', parsedArgs.account?.toString() ?? '')
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VoiceAssistantController::assistant
* @see app/Http/Controllers/VoiceAssistantController.php:351
* @param account - Default: '$subdomain'
* @route '/{account?}/audio/assistant'
*/
assistant.post = (args?: { account?: string | number } | [account: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: assistant.url(args, options),
    method: 'post',
})

const audio = {
    transcribe: Object.assign(transcribe, transcribe),
    command: Object.assign(command, command),
    assistant: Object.assign(assistant, assistant),
}

export default audio