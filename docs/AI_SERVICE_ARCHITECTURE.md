# AI Service Architecture

The AI service is used for Git Reports, Audio Transcription and voice command processing.



## Components

### 1. AIPromptRegistry (`app/Services/AI/AIPromptRegistry.php`)

Central registry for all AI prompts. Provides static methods to retrieve prompts:

- `getGitReportSystemPrompt()`: System prompt for git report generation
- `getTechnicalGitPrompt()`: User prompt for technical reports
- `getNonTechnicalGitPrompt()`: User prompt for non-technical reports
- `getVoiceCommandSystemPrompt()`: System prompt for voice command processing
- `getVoiceAssistantSystemPrompt()`: System prompt for voice assistant transcription


### 2. AIActionInterface (`app/Services/AI/Contracts/AIActionInterface.php`)

Interface defining the contract for AI action implementations:

```php
interface AIActionInterface
{
    public function generateText(string $systemPrompt, string $userPrompt): string;
    public function transcribeAudio(UploadedFile $audioFile): string;
    public function textToCommand(string $systemPrompt, string $text): array;
    public function textToAssistant(string $system_prompt, string $user_input);
}
```

### 3. PrismAIAction (`app/Services/AI/Actions/PrismAIAction.php`)

Direct implementation using Prism library:
- `generateText()`: Uses Prism with Gemini for text generation
- `transcribeAudio()`: Uses OpenAI Whisper API for audio transcription
- `textToCommand()`: Not implemented (delegates to N8n)
- `textToAssistant()`: Not implemented (delegates to N8n)

### 4. N8nAIAction (`app/Services/AI/Actions/N8nAIAction.php`)

Implementation that offloads processing to N8n workflows:
- `generateText()`: Not implemented (uses Prism)
- `transcribeAudio()`: Not implemented (uses Prism)
- `textToCommand()`: Sends short voice requests to N8n webhook
- `textToAssistant()`: Sends requests to N8n webhook, passing system prompt and user input
for complect voice assistant tasks

### 5. AIService (`app/Services/AIService.php`)

- Receives AI action implementations via dependency injection
- Uses AIPromptRegistry for all prompts
- Delegates actual AI work to injected implementations

### 6. AIServiceProvider (`app/Providers/AIServiceProvider.php`)

Registers all AI-related services:
- Binds Prism and N8n implementations
- Configures AIService with appropriate implementations
- Uses text generation via Prism
- Uses command processing via N8n

## Configuration

AI settings are configured in `config/ai.php`:

```php
return [
    'text_generation' => [
        'provider' => env('AI_TEXT_PROVIDER', 'gemini'),
        'model' => env('AI_TEXT_MODEL', 'gemini-2.0-flash'),
    ],
    'n8n' => [
        'webhook_url' => env('AI_N8N_WEBHOOK_URL', '...'),
    ],
    'driver' => env('AI_DRIVER', 'prism'),
];
```

### Environment Variables

Add these to your `.env` file:

```env
AI_DRIVER=prism
AI_TEXT_PROVIDER=gemini
AI_TEXT_MODEL=gemini-2.0-flash
AI_N8N_WEBHOOK_URL=http://host.docker.internal:5678/webhook/...
```

## Usage

The public API of AIService remains the same:

```php
// Inject AIService
public function __construct(AIService $aiService)
{
    $this->aiService = $aiService;
}

// Generate git summary
$report = $this->aiService->generateGitSummary($log, null, ReportTypes::TECHNICAL);

// Transcribe audio
$transcript = $this->aiService->transcribeAudio($audioFile);

// Convert text to command
$command = $this->aiService->textToCommand($text, $extraData);
```

## Benefits of This Architecture

1. **Better Separation of Concerns**: Each class has a single responsibility
2. **Easier Testing**: Dependencies can be mocked easily
3. **Flexibility**: Easy to swap implementations (Prism ↔ N8n)
4. **Maintainability**: Prompts and config are centralized
5. **Scalability**: Easy to add new AI actions or providers
6. **Clean Code**: No hardcoded values or domain-specific properties in the service

## Future Enhancements

- Implement full Prism-based `textToCommand()` as an alternative to N8n
- Add caching layer for AI responses
- Implement rate limiting
- Add monitoring and metrics
- Support for multiple AI providers simultaneously
