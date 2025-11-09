<?php

namespace App\Services;

use App\Enums\ReportTypes;
use App\Services\AI\AIPromptRegistry;
use App\Services\AI\Contracts\AIActionInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Refactored AI Service with better separation of concerns
 * Uses AIPromptRegistry for prompts and AIActionInterface for interchangeable AI implementations
 */
class AIService
{
    /**
     * The AI action implementation (Prism or N8n)
     */
    private AIActionInterface $textAction;

    private AIActionInterface $commandAction;

    /**
     * Constructor - inject AI action implementations
     */
    public function __construct(AIActionInterface $textAction, AIActionInterface $commandAction)
    {
        $this->textAction = $textAction;
        $this->commandAction = $commandAction;
    }

    /**
     * Generate a git summary report using AI
     *
     * @param  string|null  $log  The git log text
     * @param  string|null  $prompt  Optional custom prompt
     * @param  ReportTypes|string|null  $reportType  The type of report to generate
     * @return string The generated report
     *
     * @throws \Exception If generation fails
     */
    public function generateGitSummary(?string $log, ?string $prompt = null, ReportTypes|string|null $reportType = ReportTypes::TASK_BASED)
    {
        if ($reportType && ! $reportType instanceof ReportTypes) {
            $reportType = ReportTypes::from($reportType);
        }

        // Select appropriate prompt from registry
        switch ($reportType) {
            case ReportTypes::TECHNICAL:
                $prompt ??= AIPromptRegistry::getTechnicalGitPrompt();
                break;
            case ReportTypes::FINANCIAL:
            case ReportTypes::OPERATIONAL:
            case ReportTypes::TASK_BASED:
            default:
                $prompt ??= AIPromptRegistry::getNonTechnicalGitPrompt();
                break;
        }

        $prompt .= "\n\nHere is the git log text output:\n\n".$log;
        $systemPrompt = AIPromptRegistry::getGitReportSystemPrompt();

        return $this->textAction->generateText($systemPrompt, $prompt);
    }

    /**
     * Transcribe audio file to text using OpenAI's Whisper API
     *
     * @param  UploadedFile  $audioFile  The audio file to transcribe
     * @return string The transcribed text
     *
     * @throws \Exception If transcription fails
     */
    public function transcribeAudio(UploadedFile $audioFile): string
    {
        return $this->textAction->transcribeAudio($audioFile);
    }

    /**
     * Convert natural language text to a structured command
     *
     * @param  string  $text  The natural language input
     * @param  array  $extraData  Extra context data (available projects, tasks, etc.)
     * @return \App\DTOs\VoiceCommand The structured command
     *
     * @throws \Exception If command generation fails
     */
    public function textToCommand(string $text, array $extraData): \App\DTOs\VoiceCommand
    {
        $availableCommands = CommandHandler::COMMANDS;
        $currentDateTime = now()->format('Y-m-d H:i:s');

        $systemPrompt = AIPromptRegistry::getVoiceCommandSystemPrompt(
            $availableCommands,
            $extraData,
            $currentDateTime
        );

        $commandData = $this->commandAction->textToCommand($systemPrompt, $text);

        return \App\DTOs\VoiceCommand::fromArray($commandData);
    }

    public function textToAssistant(string $text)
    {
        $systemPrompt = AIPromptRegistry::getVoiceAssistantSystemPrompt($text);
        $response = $this->commandAction->textToAssistant($systemPrompt, $text);
        \Log::debug('AIService textToAssistant response', ['response' => $response]);
        return $response;
    }

    public function getMcpEndpointUrl(?string $tenantHost = null): string
    {
        $tenant = \App\Models\Landlord\Tenant::current() 
            ?? \App\Models\Landlord\Tenant::where('host', $tenantHost)->first();
        if(!$tenant) {
             throw new \Exception('No tenant found for MCP endpoint URL generation');
        }
        $route = route('mcp.qadran');
        if (Str::contains($route, 'localhost')) {
            $route = 'http://host.docker.internal:' . parse_url($route, PHP_URL_PORT) . '/mcp/qadran';
        }
        $route .= '?tenant=' . $tenant->host;
        return $route;
    }
}
