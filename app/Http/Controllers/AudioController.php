<?php

namespace App\Http\Controllers;

use App\Jobs\StoreVoiceCommandJob;
use App\Models\Landlord\Tenant;
use App\Models\Project;
use App\Services\AIService;
use App\Services\CommandHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AudioController extends Controller
{
    public function __construct(
        private AIService $aiService
    ) {
    }

    public function command(Request $request)
    {
        // Receive text and call AI service to convert to command
        try {
            $request->validate([
                'text' => 'required|string|max:1000',
            ]);

            $text = $request->input('text');
            $userId = $request->user()->id;

            $projects = $request->user()->projects()->select('name', 'projects.id')->pluck('id', 'name');

            $voiceCommand = $this->aiService->textToCommand($text, compact('projects'));
            \Log::info('Text to command result', [
                'command' => $voiceCommand->command,
                'params' => $voiceCommand->params,
            ]);
            // Extract data for database storage
            $commandData = [
                'command' => $voiceCommand->command,
                'params' => array_map(fn($p) => $p->jsonSerialize(), $voiceCommand->params),
            ];

            $metadata = array_merge(
                $voiceCommand->metadata ?? [],
                [
                    'confidence' => $voiceCommand->confidence,
                    'processed_at' => now()->toIso8601String(),
                ]
            );

            // Save the voice command to database asynchronously with tenant context
            StoreVoiceCommandJob::dispatch(
                userId: $userId,
                transcript: $text,
                command: $commandData,
                metadata: $metadata,
            );

            // Execute the command
            $result = CommandHandler::handleCommand($voiceCommand->command, $voiceCommand->params);

            if (!$result) {
                return back()->with('data', [
                    'transcript' => $text,
                    'command' => $voiceCommand->jsonSerialize(),
                    'error' => 'Command execution failed or not implemented',
                ]);
            }
            return back()->with('data', [
                'transcript' => $text,
                'command' => $voiceCommand->jsonSerialize(),
                'success' => 'Command executed successfully',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e; // Let Inertia handle validation errors
        } catch (\Exception $e) {
            Log::error('Text to command processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function transcribe(Request $request)
    {
        try {
            $request->validate([
                'audio' => 'required|file|mimes:webm,wav,mp3,ogg,m4a|max:10240', // 10MB max
            ]);
            $userId = $request->user()->id;

            $projects = $request->user()->projects()->select('name', 'projects.id')->pluck('id', 'name');

            $audioFile = $request->file('audio');

            if (!$audioFile) {
                return back()->with('data', [
                    'audioError' => 'No audio file provided',
                ]);
            }

            // Get the transcript from the AI service
            $transcript = $this->aiService->transcribeAudio($audioFile);
            $voiceCommand = $this->aiService->textToCommand($transcript, compact('projects'));

            // Extract data for database storage
            $commandData = [
                'command' => $voiceCommand->command,
                'params' => array_map(fn($p) => $p->jsonSerialize(), $voiceCommand->params),
            ];

            $metadata = array_merge(
                $voiceCommand->metadata ?? [],
                [
                    'confidence' => $voiceCommand->confidence,
                    'processed_at' => now()->toIso8601String(),
                ]
            );

            // Save the voice command to database asynchronously with tenant context
            StoreVoiceCommandJob::dispatch(
                userId: $userId,
                transcript: $transcript,
                command: $commandData,
                metadata: $metadata,
            );

            // Execute the command
            $result = CommandHandler::handleCommand($voiceCommand->command, $voiceCommand->params);

            if (!$result) {
                return back()->with('data', [
                    'transcript' => $transcript,
                    'command' => $voiceCommand->jsonSerialize(),
                    'error' => 'Command execution failed or not implemented',
                ]);
            }

            return back()->with('data', [
                'transcript' => $transcript,
                'command' => $voiceCommand->jsonSerialize(),
                'success' => 'Command executed successfully',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e; // Let Inertia handle validation errors

        } catch (\Exception $e) {
            Log::error('Audio transcription failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('data', [
                'audioError' => 'Failed to transcribe audio: ' . $e->getMessage(),
            ]);
        }
    }

    public function transcribeToCommand(Request $request)
    {
        try {
            throw new \Exception('Debug');
            $request->validate([
                'audio' => 'required|file|mimes:webm,wav,mp3,ogg,m4a|max:10240', // 10MB max
            ]);

            $audioFile = $request->file('audio');

            if (!$audioFile) {
                return back()->with('data', [
                    'audioError' => 'No audio file provided',
                ]);
            }

            // Get the transcript and command from the AI service
            $transcript = $this->aiService->transcribeAudio($audioFile);
            $cmd = $this->aiService->textToCommand($transcript);
            $userId = $request->user()->id;

            \Log::debug('Handling command1', [
                'command' => $cmd->command,
                'params' => $cmd->params,
            ]);

            // Save the voice command to database asynchronously with tenant context
            StoreVoiceCommandJob::dispatch(
                userId: $userId,
                transcript: $transcript,
                command: $cmd instanceof \App\DTOs\VoiceCommand ? $cmd->jsonSerialize() : null,
            );

            \Log::debug('Handling command2', [
                'command' => $cmd->command,
                'params' => $cmd->params,
            ]);

            if(! CommandHandler::handleCommand($cmd->command, $cmd->params)) {
                return back()->with('data', [
                    'transcript' => $transcript,
                    'command' => $cmd,
                ]);
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e; // Let Inertia handle validation errors

        } catch (\Exception $e) {
            Log::error('Audio to command transcription failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('data', [
                'audioError' => 'Failed to transcribe audio to command: ' . $e->getMessage(),
            ]);
        }
    }
}
