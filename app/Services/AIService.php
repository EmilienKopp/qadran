<?php

namespace App\Services;

use App\Enums\ReportTypes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;
use Prism\Prism\Prism;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Exceptions\PrismException;

class AIService
{

  public string $system = 'You are a helpful assistant that generates concise and informative daily reports based on git log text output. 
    Your job is to help developers quickly report to stakeholders, to show what they have done in a day or week.
    Depening on the context, you will generate a report that is either technical or non-technical.
    Technical reports will include development activity, and brush over techincal tasks performed.
    Non-technical reports will list a high-level, "executive summary" of the work done, without technical jargon,
    and will be suitable for stakeholders to understand what features or tasks were completed without needing to know the technical.
    Tasks might be things like "Bug fixes on feature X", "Implemented feature Y", "Refactored code for better performance", etc... and
    will NOT include file names, class names, or any other technical jargon that a non-technical stakeholder would not understand or care about.
    If the logs have dates, you will summarize the work done **PER DAY**, and if the logs do not have dates, you will summarize the work done in a single report.
  ';
  private string $dev_prompt = 'You will generate a report of development activity based on this git log text output. The report will be concise, informative, easy to read at a glance. t will **NOT** contain commit information.';

  private string $non_tech_prompt = 'You will generate a non-tech stakeholder friendly report of activity based on this git log text output. The report will be concise, informative, easy to read at a glance. It will **NOT** contain commit information,
    but rather describe the work in terms of features and tasks worked on.
  ';
  public function generateGitSummary(?string $log, ?string $prompt = null, ReportTypes|string|null $reportType = ReportTypes::TASK_BASED)
  {
    if ($reportType && !$reportType instanceof ReportTypes) {
      $reportType = ReportTypes::from($reportType);
    }
    switch ($reportType) {
      case ReportTypes::TECHNICAL:
        $prompt ??= $this->dev_prompt;
        break;
      case ReportTypes::FINANCIAL:
      case ReportTypes::OPERATIONAL:
      case ReportTypes::TASK_BASED:
      default:
        $prompt ??= $this->non_tech_prompt;
        break;
    }

    $prompt .= "\n\nHere is the git log text output:\n\n" . $log;

    try {
      $response = Prism::text()
        ->using(Provider::Gemini, 'gemini-2.0-flash')
        ->withSystemPrompt($this->system)
        ->withPrompt($prompt)
        ->asText();

      return $response->text;

    } catch (PrismException $e) {
      Log::error('Text generation failed:', ['error' => $e->getMessage()]);
      throw $e;
    } catch (\Throwable $e) {
      Log::error('Unknown error:', ['error' => $e->getMessage()]);
      throw $e;
    }
  }

  /**
   * Transcribe audio file to text using OpenAI's Whisper API
   *
   * @param UploadedFile $audioFile The audio file to transcribe
   * @return string The transcribed text
   * @throws \Exception If transcription fails
   */
  public function transcribeAudio(UploadedFile $audioFile): string
  {
    $apiKey = config('prism.providers.openai.api_key');

    if (empty($apiKey)) {
      throw new \Exception('OpenAI API key is not configured. Please set OPENAI_API_KEY in your .env file.');
    }

    try {
      // OpenAI's Whisper API endpoint
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

      if (!$response->successful()) {
        Log::error('OpenAI Whisper API error:', [
          'status' => $response->status(),
          'body' => $response->body(),
        ]);
        throw new \Exception('Audio transcription failed: ' . $response->body());
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
   * Convert natural language text to a structured command via n8n webhook
   *
   * @param string $text The natural language input
   * @return \App\DTOs\VoiceCommand The structured command
   * @throws \Exception If command generation fails
   */
  public function textToCommand(string $text, array $extraData): \App\DTOs\VoiceCommand
  {
    $availableCommands = CommandHandler::COMMANDS;

    $systemPrompt = "You are an AI assistant that converts voice commands into structured commands for a developer productivity application.

Your job is to parse natural language input and extract:
1. The command name from the available commands
2. All relevant parameters with their proper types (string, number, boolean, date, datetime, time, array, object)
3. Parameter labels that match common field names

Available commands:
" . implode("\n", array_map(fn($cmd, $desc) => "- {$cmd}: {$desc}", array_keys($availableCommands), $availableCommands)) . "

Extra context data (available projects, tasks):
" . json_encode($extraData, JSON_PRETTY_PRINT) . "

Guidelines:
- Extract dates in ISO 8601 format (YYYY-MM-DD HH:mm:ss)
- Use 'number' type for hours, quantities, IDs
- Use 'string' type for names, descriptions, text content
- Use 'boolean' type for true/false flags
- Use 'date' or 'datetime' type for date/time values
- Common labels: task, title, description, hours, from, to, date, id, name, content, keyword, status, period, project, project_id, task_id
- If user doesn't specify a date/time, use current date/time: " . now()->format('Y-m-d H:i:s') . "
- Set confidence (0-1) based on how clear the user's intent is
- Mark parameters as optional: true if they weren't explicitly mentioned

Clock in/out specific guidelines:
- clock_in: Requires either 'project', 'project_id', 'task', or 'task_id' parameter. Optional 'timestamp' for backdated clock-ins
- clock_out: Optional 'timestamp' for backdated clock-outs. No project/task needed (assumes current active session)
- Match project names from the provided projects list (case-insensitive). Use project_id if exact match found
- Examples: 'clock in to project X', 'start working on task Y', 'clock out', 'stop timer'

Example output structures:
{
  \"command\": \"clock_in\",
  \"params\": [
    {\"label\": \"project_id\", \"type\": \"number\", \"value\": 5},
    {\"label\": \"project\", \"type\": \"string\", \"value\": \"invoicing\"},
    {\"label\": \"timestamp\", \"type\": \"datetime\", \"value\": \"" . now()->format('Y-m-d H:i:s') . "\", \"optional\": true}
  ],
  \"confidence\": 0.95
}

{
  \"command\": \"clock_out\",
  \"params\": [
    {\"label\": \"timestamp\", \"type\": \"datetime\", \"value\": \"" . now()->format('Y-m-d H:i:s') . "\", \"optional\": true}
  ],
  \"confidence\": 0.98
}";

    try {
      $client = new \GuzzleHttp\Client();
      $response = $client->post('http://host.docker.internal:5678/webhook/4ffc04bd-d7c9-46b7-be49-1245185ae742', [
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
        throw new \Exception('Command generation failed: ' . $response->getBody()->getContents());
      }

      [$data] = json_decode($response->getBody()->getContents(), true);

      return \App\DTOs\VoiceCommand::fromArray($data['output'] ?? []);

    } catch (\GuzzleHttp\Exception\GuzzleException $e) {
      Log::error('n8n webhook request failed:', [
        'error' => $e->getMessage(),
      ]);
      throw new \Exception('Failed to connect to command processing service: ' . $e->getMessage());
    } catch (\Exception $e) {
      Log::error('Command generation failed:', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
      ]);
      throw $e;
    }
  }
}