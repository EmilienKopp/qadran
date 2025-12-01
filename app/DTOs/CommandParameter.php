<?php

namespace App\DTOs;

use JsonSerializable;

class CommandParameter implements JsonSerializable
{
    public function __construct(
        public string $label,
        public string $type,
        public mixed $value,
        public bool $optional = false,
        public ?string $description = null,
    ) {}

    public function jsonSerialize(): array
    {
        return array_filter([
            'label' => $this->label,
            'type' => $this->type,
            'value' => $this->value,
            'optional' => $this->optional,
            'description' => $this->description,
        ], fn ($value) => $value !== null || $value !== false);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            label: $data['label'],
            type: $data['type'],
            value: $data['value'],
            optional: $data['optional'] ?? false,
            description: $data['description'] ?? null,
        );
    }

    /**
     * Type-safe value getter with casting
     */
    public function getTypedValue(): mixed
    {
        return match ($this->type) {
            'string' => (string) $this->value,
            'number' => is_numeric($this->value) ? (float) $this->value : $this->value,
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'date', 'datetime' => new \DateTime($this->value),
            'time' => \DateTime::createFromFormat('H:i:s', $this->value),
            'array' => is_array($this->value) ? $this->value : json_decode($this->value, true),
            'object' => is_object($this->value) ? $this->value : json_decode($this->value),
            default => $this->value,
        };
    }
}
