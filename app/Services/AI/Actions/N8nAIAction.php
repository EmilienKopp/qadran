<?php

namespace App\Services\AI\Actions;

use App\Services\AI\Contracts\AIActionInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

/**
 * N8n webhook implementation of AI actions
 * Offloads AI processing to external n8n workflows
 */
class N8nAIAction implements AIActionInterface
{
    /**
     * Generate text using n8n webhook (not implemented)
     */
    public function generateText(string $systemPrompt, string $userPrompt): string
    {
        throw new \Exception('Text generation via n8n is not implemented. Use PrismAIAction instead.');
    }

    /**
     * Transcribe audio using n8n webhook (not implemented)
     */
    public function transcribeAudio(UploadedFile $audioFile): string
    {
        throw new \Exception('Audio transcription via n8n is not implemented. Use PrismAIAction instead.');
    }

    /**
     * Process text to command using n8n webhook
     */
    public function textToCommand(string $systemPrompt, string $text): array
    {
        $webhookUrl = config('ai.n8n.webhook_url');

        if (empty($webhookUrl)) {
            throw new \Exception('N8n webhook URL is not configured. Please set AI_N8N_WEBHOOK_URL in your .env file.');
        }

        try {
            $client = new \GuzzleHttp\Client;
            $response = $client->post($webhookUrl, [
                'json' => [
                    'system_prompt' => $systemPrompt,
                    'user_input' => $text,
                    'timestamp' => now()->toIso8601String(),
                ],
                'timeout' => 30,
            ]);

            if ($response->getStatusCode() !== 200) {
                Log::error('n8n webhook error:', [
                    'status' => $response->getStatusCode(),
                    'body' => $response->getBody()->getContents(),
                ]);
                throw new \Exception('Command generation failed: '.$response->getBody()->getContents());
            }

            [$data] = json_decode($response->getBody()->getContents(), true);
            \Log::info('n8n webhook response:', $data);
            return $data['output'] ?? [];

        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            Log::error('n8n webhook request failed:', [
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Failed to connect to command processing service: '.$e->getMessage());
        } catch (\Exception $e) {
            Log::error('Command generation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
