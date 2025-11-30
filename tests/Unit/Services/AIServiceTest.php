<?php

namespace Tests\Unit\Services;

use App\Enums\ReportTypes;
use App\Services\AI\Contracts\AIActionInterface;
use App\Services\AIService;
use Mockery;
use Tests\TestCase;

class AIServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_generate_git_summary_uses_technical_prompt_for_technical_report()
    {
        $textAction = Mockery::mock(AIActionInterface::class);
        $commandAction = Mockery::mock(AIActionInterface::class);

        $textAction->shouldReceive('generateText')
            ->once()
            ->with(
                Mockery::type('string'),
                Mockery::on(function ($arg) {
                    return strpos($arg, 'development activity') !== false;
                })
            )
            ->andReturn('Generated report');

        $service = new AIService($textAction, $commandAction);
        $result = $service->generateGitSummary('git log output', null, ReportTypes::TECHNICAL);

        $this->assertEquals('Generated report', $result);
    }

    public function test_generate_git_summary_uses_non_technical_prompt_for_task_based_report()
    {
        $textAction = Mockery::mock(AIActionInterface::class);
        $commandAction = Mockery::mock(AIActionInterface::class);

        $textAction->shouldReceive('generateText')
            ->once()
            ->with(
                Mockery::type('string'),
                Mockery::on(function ($arg) {
                    return strpos($arg, 'non-tech') !== false;
                })
            )
            ->andReturn('Generated report');

        $service = new AIService($textAction, $commandAction);
        $result = $service->generateGitSummary('git log output', null, ReportTypes::TASK_BASED);

        $this->assertEquals('Generated report', $result);
    }

    public function test_transcribe_audio_delegates_to_text_action()
    {
        $textAction = Mockery::mock(AIActionInterface::class);
        $commandAction = Mockery::mock(AIActionInterface::class);

        $audioFile = Mockery::mock(\Illuminate\Http\UploadedFile::class);

        $textAction->shouldReceive('transcribeAudio')
            ->once()
            ->with($audioFile)
            ->andReturn('Transcribed text');

        $service = new AIService($textAction, $commandAction);
        $result = $service->transcribeAudio($audioFile);

        $this->assertEquals('Transcribed text', $result);
    }
}
