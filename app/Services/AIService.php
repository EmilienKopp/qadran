<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;

class AIService
{
  private string $apiKey;
  private string $baseUrl;
  private string $url;

  public function __construct()
  {
    Log::info(config('services.ai'));
    $this->apiKey = config('services.ai.key');
    $this->baseUrl = config('services.ai.base_url');
    $version = config('services.ai.version');
    $endpoint = config('services.ai.endpoint');
    $this->url = "{$this->baseUrl}/{$version}/{$endpoint}";
  }

  public function generateGitSummary(string $prompt): array
  {
    try {
      $response = Http::withHeaders([
        'Authorization' => "Bearer {$this->apiKey}",
        // 'x-api-key' => $this->apiKey,
        // 'anthropic-version' => '2023-06-01',
        'Content-Type' => 'application/json',
      ])->post($this->url, [
        'model' => 'gpt-4-turbo',
        'messages' => [
          ['role' => 'developer', 'content' => 'You will generate a daily report of development activity based on this git log text output. The report will be concise, informative, easy to read at a glance. It will NOT contain commit information.'],
          ['role' => 'user', 'content' => $prompt]
        ],
        'max_tokens' => 4096,
        // 'system' => 'You will generate a daily report of development activity based on this git log text output. The report will be concise, informative, easy to read at a glance.'
      ]);

      if ($response->failed()) {
        throw new Exception('AI API request failed: ' . $response->body());
      }

      return $response->json();

    } catch (Exception $e) {
      // Log the error
      \Log::error('AI Service Error: ' . $e->getMessage());
      throw $e;
    }
  }
}