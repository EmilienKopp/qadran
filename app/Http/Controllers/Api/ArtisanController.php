<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArtisanController extends Controller
{
    public function run(Request $request)
    {

        $command = $request->query('command');
        $arguments = $request->query('arguments', []);
        $options = $request->query('options', []);
        $useStream = filter_var($request->query('use_stream', false), FILTER_VALIDATE_BOOLEAN);
        $commandAllowed = $command && in_array(explode(' ', $command)[0], config('cli.whitelist')); // Whitelist commands

        if (! $command || ! $commandAllowed) {
            \Log::warning('Attempt to run invalid or unauthorized command: '.($command ?? 'null'));
            if ($useStream) {
                return response()->stream(function () {
                    echo 'data: '.json_encode(['message' => "Invalid command.\n", 'done' => true, 'error' => true])."\n\n";
                    ob_flush();
                    flush();
                }, 400, [
                    'Content-Type' => 'text/event-stream',
                    'Cache-Control' => 'no-cache',
                    'Connection' => 'keep-alive',
                    'X-Accel-Buffering' => 'no',
                ]);
            }

            return response()->json(['error' => 'Invalid command'], 400);
        }

        // Extract potential arguments and options from the command string, and merge to the arrays
        $parts = explode(' ', $command);
        $command = array_shift($parts);
        foreach ($parts as $part) {
            $part = trim($part);
            if (str_starts_with($part, '--')) {
                $optionPart = substr($part, 2);
                if (str_contains($optionPart, '=')) {
                    [$key, $value] = explode('=', $optionPart, 2);
                    $options[$key] = $value;
                } else {
                    $options[$optionPart] = true;
                }
            } else {
                $arguments[] = $part;
            }
        }

        $arguments = array_values($arguments); // Reindex
        $options = array_merge(array_map(fn ($v) => is_bool($v) ? filter_var($v, FILTER_VALIDATE_BOOLEAN) : $v, $options), ['no-interaction' => true]);

        $forwarding = \App\Services\IO\Forwarding::make($command, $arguments, $options);

        try {
            if ($useStream) {
                // Disable FastCGI buffering BEFORE the response
                if (function_exists('fastcgi_finish_request')) {
                    header('X-Accel-Buffering: no');
                }

                return response()->stream($forwarding->asStream(), 200, [
                    'Content-Type' => 'text/event-stream',
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    'Connection' => 'keep-alive',
                    'X-Accel-Buffering' => 'no', // Nginx
                    'X-Content-Type-Options' => 'nosniff',
                ]);
            }

            $output = $forwarding->run();

            return response()->json(['output' => $output], 200);
        } catch (\Exception $e) {
            \Log::error('Artisan command error: '.$e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
