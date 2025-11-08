<?php

namespace App\Services\AI\Actions;

use App\Services\AI\Contracts\AIActionInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Exceptions\PrismException;
use Prism\Prism\Prism;

/**
 * Direct Prism implementation of AI actions
 */
class PrismAIAction implements AIActionInterface
{
    /**
     * Generate text using Prism with Gemini
     */
    public function generateText(string $systemPrompt, string $userPrompt): string
    {
        try {
            $provider = config('ai.text_generation.provider', Provider::Gemini);
            $model = config('ai.text_generation.model', 'gemini-2.0-flash');

            $response = Prism::text()
                ->using($provider, $model)
                ->withSystemPrompt($systemPrompt)
                ->withPrompt($userPrompt)
                ->asText();

            return $response->text;

        } catch (PrismException $e) {
            Log::error('Text generation failed:', ['error' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Unknown error during text generation:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Transcribe audio using OpenAI Whisper API
     */
    public function transcribeAudio(UploadedFile $audioFile): string
    {
        $apiKey = config('prism.providers.openai.api_key');

        if (empty($apiKey)) {
            throw new \Exception('OpenAI API key is not configured. Please set OPENAI_API_KEY in your .env file.');
        }

        try {
            $response = Http::withToken($apiKey)
                ->attach(
                    'file',
                    file_get_contents($audioFile->getPathname()),
                    $audioFile->getClientOriginalName()
                )
                ->post('https://api.openai.com/v1/audio/transcriptions', [
                    'model' => 'whisper-1',
                    'response_format' => 'json',
                ]);

            if (! $response->successful()) {
                Log::error('OpenAI Whisper API error:', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception('Audio transcription failed: '.$response->body());
            }

            $data = $response->json();

            return $data['text'] ?? '';

        } catch (\Exception $e) {
            Log::error('Audio transcription failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Process text to command using Prism (not implemented - use N8nAIAction instead)
     */
    public function textToCommand(string $systemPrompt, string $text): array
    {
        // This could be implemented with Prism, but currently n8n is preferred
        throw new \Exception('Text to command is not implemented in PrismAIAction. Use N8nAIAction instead.');
    }

    public function textToAssistant(string $system_prompt, string $user_input)
    {
        // This could be implemented with Prism, but currently n8n is preferred
        throw new \Exception('Text to assistant is not implemented in PrismAIAction. Use N8nAIAction instead.');
    }
}
