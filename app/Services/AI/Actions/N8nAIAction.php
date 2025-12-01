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
    public function textToCommand(string $system_prompt, string $user_input): array
    {
        $webhookUrl = config('services.n8n.webhook_url');

        if (empty($webhookUrl)) {
            throw new \Exception('N8n webhook URL is not configured. Please set AI_N8N_WEBHOOK_URL');
        }

        return $this->executeWebhook($webhookUrl, $system_prompt, $user_input);
    }

    public function textToAssistant(string $system_prompt, string $user_input, ?string $webhookUrl = null)
    {
        $webhookUrl ??= config('services.n8n.assistant_webhook_url');

        if (empty($webhookUrl)) {
            throw new \Exception('N8n assistant webhook URL is not configured. Please set AI_N8N_ASSISTANT_WEBHOOK_URL');
        }

        return $this->executeWebhook($webhookUrl, $system_prompt, $user_input);
    }

    private function executeWebhook(string $url, string $system_prompt, string $user_input)
    {
        \Log::debug('Executing n8n webhook', [
            'url' => $url,
            'system_prompt' => $system_prompt,
            'user_input' => $user_input,
        ]);
        try {
            $client = new \GuzzleHttp\Client;
            $tenantId = hash('sha256', \App\Models\Landlord\Tenant::current()?->id.env('APP_KEY'));
            $response = $client->post($url, [
                'json' => [
                    'timestamp' => now()->toIso8601String(),
                    'token' => request()->user()->tokens()->first()?->token,
                    'system_prompt' => $system_prompt,
                    'user_input' => $user_input,
                    'tenant_id' => $tenantId,
                ],
                'timeout' => 30,
            ]);

            if ($response->getStatusCode() !== 200) {
                Log::error('n8n webhook error:', [
                    'status' => $response->getStatusCode(),
                    'body' => $response->getBody()->getContents(),
                ]);
                throw new \Exception('Webhook execution failed: '.$response->getBody()->getContents());
            }

            [$data] = json_decode($response->getBody()->getContents(), true);
            \Log::info('n8n webhook response:', $data ?? []);

            return $data['output'] ?? [];

        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            Log::error('n8n webhook request failed:', [
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Failed to connect to webhook service: '.$e->getMessage());
        } catch (\Exception $e) {
            Log::error('Webhook execution failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
