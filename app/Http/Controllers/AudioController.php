<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AudioController extends Controller
{
    public function __construct(
        private AIService $aiService
    ) {}

    public function transcribe(Request $request)
    {
        try {
            $request->validate([
                'audio' => 'required|file|mimes:webm,wav,mp3,ogg,m4a|max:10240', // 10MB max
            ]);

            $audioFile = $request->file('audio');

            if (!$audioFile) {
                return back()->with('data', [
                    'audioError' => 'No audio file provided',
                ]);
            }

            // Get the transcript from the AI service
            $transcript = $this->aiService->transcribeAudio($audioFile);

            return back()->with('data', [
                'transcript' => $transcript,
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
}
