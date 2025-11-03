<?php

namespace App\Services\AI\Contracts;

use Illuminate\Http\UploadedFile;

/**
 * Interface for AI action implementations
 * Allows switching between direct Prism calls and external services like n8n
 */
interface AIActionInterface
{
    /**
     * Generate text using AI based on system and user prompts
     *
     * @param  string  $systemPrompt  The system/context prompt
     * @param  string  $userPrompt  The user's input/question
     * @return string The generated text response
     *
     * @throws \Exception If generation fails
     */
    public function generateText(string $systemPrompt, string $userPrompt): string;

    /**
     * Transcribe audio to text
     *
     * @param  UploadedFile  $audioFile  The audio file to transcribe
     * @return string The transcribed text
     *
     * @throws \Exception If transcription fails
     */
    public function transcribeAudio(UploadedFile $audioFile): string;

    /**
     * Process text to extract structured command data
     *
     * @param  string  $systemPrompt  The system prompt defining command structure
     * @param  string  $text  The user's natural language input
     * @return array The structured command data
     *
     * @throws \Exception If command extraction fails
     */
    public function textToCommand(string $systemPrompt, string $text): array;
}
