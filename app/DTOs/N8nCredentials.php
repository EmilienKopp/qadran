<?php

namespace App\DTOs;

use JsonSerializable;

class N8nCredentials implements JsonSerializable
{
    /**
     * @param  string  $id  Credential ID in n8n
     * @param  string  $name  Credential name/label in n8n
     */
    public function __construct(
        public string $id,
        public string $name,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
        );
    }

    /**
     * Convert to array format expected by N8NService
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    /**
     * Check if credentials are valid
     */
    public function isValid(): bool
    {
        return ! empty($this->id) && ! empty($this->name);
    }
}
