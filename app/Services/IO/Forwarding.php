<?php

namespace App\Services\IO;

use Illuminate\Support\Facades\Artisan;

class Forwarding
{
    private array $context;

    private string $command;

    private array $arguments;

    private array $options;

    public static function make(string $command, array $arguments = [], array $options = []): Forwarding
    {
        return new self($command, $arguments, $options);
    }

    public function __construct(string $command, array $arguments = [], array $options = [])
    {
        $this->command = $command;
        $this->arguments = $arguments;
        $this->options = array_merge($options, [
            'tenant' => tenant()?->id, // Pass current tenant ID if available
            'userId' => auth()?->id(), // Pass current user ID if available
        ]);
        $this->context = [];
    }

    public function withContext(array $context): Forwarding
    {
        $this->context = $context;

        return $this;
    }

    public function run(): string
    {
        Artisan::call($this->command, array_merge($this->arguments, $this->options));

        return Artisan::output();
    }

    public function asStream()
    {
        return function () {
            // Disable output buffering
            while (ob_get_level() > 0) {
                ob_end_flush();
            }
            ob_implicit_flush(true);

            // Build command
            $commandArray = [PHP_BINARY, base_path('artisan'), $this->command];

            foreach ($this->arguments as $key => $value) {
                if (is_numeric($key)) {
                    $commandArray[] = $value;
                } else {
                    $commandArray[] = "{$key}={$value}";
                }
            }

            foreach ($this->options as $key => $value) {
                if (is_bool($value) && $value) {
                    $commandArray[] = "--{$key}";
                } else {
                    $commandArray[] = "--{$key}={$value}";
                }
            }

            $process = new \Symfony\Component\Process\Process($commandArray, base_path());
            $process->setTimeout(300);

            try {
                echo 'data: '.json_encode([
                    'message' => "Starting command: {$this->command}\n\n",
                    'done' => false,
                ])."\n\n";
                @ob_flush();
                @flush();

                $process->start();

                // Stream output in real-time
                while ($process->isRunning()) {
                    $output = $process->getIncrementalOutput();
                    $error = $process->getIncrementalErrorOutput();

                    if ($output) {
                        echo 'data: '.json_encode([
                            'message' => $output,
                            'done' => false,
                        ])."\n\n";
                        @ob_flush();
                        @flush();
                    }

                    if ($error) {
                        echo 'data: '.json_encode([
                            'message' => $error,
                            'done' => false,
                            'error' => true,
                        ])."\n\n";
                        @ob_flush();
                        @flush();
                    }

                    usleep(100000); // 100ms - check for output frequently
                }

                // Get any remaining output
                $remaining = $process->getIncrementalOutput();
                if ($remaining) {
                    echo 'data: '.json_encode([
                        'message' => $remaining,
                        'done' => false,
                    ])."\n\n";
                    @ob_flush();
                    @flush();
                }

                $exitCode = $process->getExitCode();
                echo 'data: '.json_encode([
                    'message' => "\nCommand completed (exit code: {$exitCode})\n",
                    'done' => true,
                    'exitCode' => $exitCode,
                ])."\n\n";
                @ob_flush();
                @flush();

            } catch (\Exception $e) {
                echo 'data: '.json_encode([
                    'message' => "Error: {$e->getMessage()}\n",
                    'done' => true,
                    'error' => true,
                ])."\n\n";
                @ob_flush();
                @flush();
            }
        };
    }
}
