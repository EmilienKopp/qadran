<?php

namespace App\Services;

use App\DTOs\CommandParameter;
use App\Repositories\ClockEntryRepository;

class CommandHandler
{
    const COMMANDS = [
        'create_task' => 'Create a new task with title, optional description, hours, and date',
        'update_task' => 'Update an existing task by ID with new values',
        'delete_task' => 'Delete a task by ID',
        'create_project' => 'Create a new project with name and optional description',
        'log_time' => 'Log time spent on a task with hours, task name/ID, and date',
        'create_note' => 'Create a quick note with content',
        'search_tasks' => 'Search for tasks by keyword or status',
        'generate_report' => 'Generate a report for a date range or period',
        'clock_in' => 'Clock in to start tracking work time, on a specific project or task',
        'clock_out' => 'Clock out to stop tracking work time',
    ];

    /**
     * Main entry point for handling voice commands
     *
     * @param  string  $command  The command name
     * @param  array  $parameters  Array of parameters (can be CommandParameter DTOs or arrays)
     * @return mixed The result of the command execution
     */
    public static function handleCommand(string $command, array $parameters): mixed
    {
        \Log::info("Handling command: {$command}", ['parameters' => $parameters]);

        if (! array_key_exists($command, self::COMMANDS)) {
            \Log::warning("Unknown command: {$command}");

            return null;
        }

        $methodName = $command;
        if (! method_exists(self::class, $methodName)) {
            \Log::warning("Command method not implemented: {$methodName}");

            return null;
        }

        return (new self)->$methodName($parameters);
    }

    public static function __callStatic($method, $args)
    {
        \Log::info("Handling command: {$method}", ['args' => $args]);
        if (array_key_exists($method, self::COMMANDS) && method_exists(self::class, $method)) {
            \Log::debug("Executing command method: {$method}");

            return (new self)->$method(...$args);
        }

        return null;
    }

    private function clock_in(array $parameters)
    {
        $params = $this->parseParams($parameters);

        $data = [
            'user_id' => auth()->id(),
            'project_id' => $params['project_id'] ?? null,
            'in' => $params['timestamp'] ?? now(),
            'timezone' => config('app.timezone'), // TODO: better handling - get from user preferences
            'notes' => $params['note'] ?? null,
        ];

        $entry = ClockEntryRepository::clockIn($data);

        return $entry ? true : false;
    }

    private function clock_out(array $parameters)
    {
        $params = $this->parseParams($parameters);

        $clockOutTime = $params['timestamp'] ?? now();
        $entry = ClockEntryRepository::clockOut(auth()->id(), $clockOutTime);

        return $entry ? true : false;
    }

    private function parseParams(array $params): array
    {
        $parsed = [];
        foreach ($params as $param) {
            if ($param instanceof CommandParameter) {
                $parsed[$param->label] = $param->getTypedValue();
            } else {
                // Handle raw array input
                $dto = CommandParameter::fromArray($param);
                $parsed[$dto->label] = $dto->getTypedValue();
            }
        }

        return $parsed;
    }
}
