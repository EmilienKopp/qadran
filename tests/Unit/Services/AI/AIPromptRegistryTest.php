<?php

namespace Tests\Unit\Services\AI;

use App\Services\AI\AIPromptRegistry;
use Tests\TestCase;

class AIPromptRegistryTest extends TestCase
{
    public function test_get_git_report_system_prompt_returns_string()
    {
        $prompt = AIPromptRegistry::getGitReportSystemPrompt();

        $this->assertIsString($prompt);
        $this->assertNotEmpty($prompt);
        $this->assertStringContainsString('git log', $prompt);
    }

    public function test_get_technical_git_prompt_returns_string()
    {
        $prompt = AIPromptRegistry::getTechnicalGitPrompt();

        $this->assertIsString($prompt);
        $this->assertNotEmpty($prompt);
        $this->assertStringContainsString('development activity', $prompt);
    }

    public function test_get_non_technical_git_prompt_returns_string()
    {
        $prompt = AIPromptRegistry::getNonTechnicalGitPrompt();

        $this->assertIsString($prompt);
        $this->assertNotEmpty($prompt);
        $this->assertStringContainsString('non-tech', $prompt);
    }

    public function test_get_voice_command_system_prompt_returns_string()
    {
        $availableCommands = [
            'create_task' => 'Create a new task',
            'clock_in' => 'Clock in to work',
        ];
        $extraData = ['projects' => []];
        $currentDateTime = '2025-11-02 12:00:00';

        $prompt = AIPromptRegistry::getVoiceCommandSystemPrompt(
            $availableCommands,
            $extraData,
            $currentDateTime
        );

        $this->assertIsString($prompt);
        $this->assertNotEmpty($prompt);
        $this->assertStringContainsString('create_task', $prompt);
        $this->assertStringContainsString('clock_in', $prompt);
        $this->assertStringContainsString($currentDateTime, $prompt);
    }
}
