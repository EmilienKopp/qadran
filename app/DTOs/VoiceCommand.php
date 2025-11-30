<?php

namespace App\DTOs;

use JsonSerializable;

class VoiceCommand implements JsonSerializable
{
    /**
     * @param  string  $command  The command name
     * @param  array<CommandParameter>  $params  Array of command parameters
     * @param  float|null  $confidence  Confidence score (0-1)
     * @param  array|null  $metadata  Additional metadata
     */
    public function __construct(
        public string $command,
        public array $params = [],
        public ?float $confidence = null,
        public ?array $metadata = null,
    ) {}

    public function jsonSerialize(): array
    {
        return array_filter([
            'command' => $this->command,
            'params' => array_map(fn (CommandParameter $p) => $p->jsonSerialize(), $this->params),
            'confidence' => $this->confidence,
            'metadata' => $this->metadata,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            command: $data['command'],
            params: array_map(
                fn (array $param) => CommandParameter::fromArray($param),
                $data['params'] ?? []
            ),
            confidence: $data['confidence'] ?? null,
            metadata: $data['metadata'] ?? null,
        );
    }

    /**
     * Get a parameter by label
     */
    public function getParam(string $label): ?CommandParameter
    {
        foreach ($this->params as $param) {
            if ($param->label === $label) {
                return $param;
            }
        }

        return null;
    }

    /**
     * Get typed value of a parameter by label
     */
    public function getParamValue(string $label): mixed
    {
        return $this->getParam($label)?->getTypedValue();
    }

    /**
     * Check if command has all required parameters
     */
    public function hasRequiredParams(array $requiredLabels): bool
    {
        $paramLabels = array_map(fn (CommandParameter $p) => $p->label, $this->params);

        return empty(array_diff($requiredLabels, $paramLabels));
    }

    /**
     * Validate command structure
     */
    public function isValid(): bool
    {
        if (empty($this->command)) {
            return false;
        }

        foreach ($this->params as $param) {
            if (! $param instanceof CommandParameter) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if this is a clock in command
     */
    public function isClockIn(): bool
    {
        return $this->command === 'clock_in';
    }

    /**
     * Check if this is a clock out command
     */
    public function isClockOut(): bool
    {
        return $this->command === 'clock_out';
    }

    /**
     * Get project identifier (ID or name) for clock in/out commands
     */
    public function getProjectIdentifier(): ?array
    {
        $projectId = $this->getParamValue('project_id');
        $projectName = $this->getParamValue('project');

        if ($projectId || $projectName) {
            return [
                'id' => $projectId,
                'name' => $projectName,
            ];
        }

        return null;
    }

    /**
     * Get task identifier (ID or name) for clock in/out commands
     */
    public function getTaskIdentifier(): ?array
    {
        $taskId = $this->getParamValue('task_id');
        $taskName = $this->getParamValue('task');

        if ($taskId || $taskName) {
            return [
                'id' => $taskId,
                'name' => $taskName,
            ];
        }

        return null;
    }

    /**
     * Get timestamp for clock in/out, defaults to now if not specified
     */
    public function getTimestamp(): \DateTime
    {
        $timestamp = $this->getParamValue('timestamp');

        if ($timestamp instanceof \DateTime) {
            return $timestamp;
        }

        if (is_string($timestamp)) {
            return new \DateTime($timestamp);
        }

        return new \DateTime;
    }
}
