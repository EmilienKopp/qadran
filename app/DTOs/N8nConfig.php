<?php

namespace App\DTOs;

use JsonSerializable;

class N8nConfig implements JsonSerializable
{
    /**
     * @param  N8nCredentials|null  $aiCredentials  AI provider credentials (Anthropic, OpenAI, etc.)
     * @param  N8nCredentials|null  $mcpCredentials  MCP Bearer token credentials
     * @param  string|null  $mcpEndpointUrl  Full URL to the MCP endpoint
     * @param  string|null  $workflowId  N8n workflow ID for this tenant/user
     * @param  string|null  $webhookUrl  Full webhook URL after workflow creation
     * @param  array|null  $preferences  Additional workflow preferences (model, timeout, etc.)
     */
    public function __construct(
        public ?N8nCredentials $aiCredentials = null,
        public ?N8nCredentials $mcpCredentials = null,
        public ?string $mcpEndpointUrl = null,
        public ?string $workflowId = null,
        public ?string $webhookUrl = null,
        public ?array $preferences = null,
    ) {}

    public function jsonSerialize(): array
    {
        return array_filter([
            'ai_credentials' => $this->aiCredentials?->jsonSerialize(),
            'mcp_credentials' => $this->mcpCredentials?->jsonSerialize(),
            'mcp_endpoint_url' => $this->mcpEndpointUrl,
            'workflow_id' => $this->workflowId,
            'webhook_url' => $this->webhookUrl,
            'preferences' => $this->preferences,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            aiCredentials: isset($data['ai_credentials'])
                ? N8nCredentials::fromArray($data['ai_credentials'])
                : null,
            mcpCredentials: isset($data['mcp_credentials'])
                ? N8nCredentials::fromArray($data['mcp_credentials'])
                : null,
            mcpEndpointUrl: $data['mcp_endpoint_url'] ?? null,
            workflowId: $data['workflow_id'] ?? null,
            webhookUrl: $data['webhook_url'] ?? null,
            preferences: $data['preferences'] ?? null,
        );
    }

    /**
     * Create from JSONB database column
     */
    public static function fromJson(?string $json): ?self
    {
        if (empty($json)) {
            return null;
        }

        $data = json_decode($json, true);

        if (! is_array($data)) {
            return null;
        }

        return self::fromArray($data);
    }

    /**
     * Convert to JSON string for database storage
     */
    public function toJson(): string
    {
        return json_encode($this->jsonSerialize());
    }

    public function toArray(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * Merge with another config (used for user overrides)
     */
    public function mergeWith(?self $override): self
    {
        if (! $override) {
            return $this;
        }

        return new self(
            aiCredentials: $override->aiCredentials ?? $this->aiCredentials,
            mcpCredentials: $override->mcpCredentials ?? $this->mcpCredentials,
            mcpEndpointUrl: $override->mcpEndpointUrl ?? $this->mcpEndpointUrl,
            workflowId: $override->workflowId ?? $this->workflowId,
            webhookUrl: $override->webhookUrl ?? $this->webhookUrl,
            preferences: $override->preferences
                ? array_merge($this->preferences ?? [], $override->preferences)
                : $this->preferences,
        );
    }

    /**
     * Check if configuration is complete enough to create a workflow
     */
    public function isValid(): bool
    {
        return $this->mcpCredentials !== null
            && $this->mcpEndpointUrl !== null;
    }

    /**
     * Get AI credentials as array for N8NService
     */
    public function getAiCredentialsArray(): ?array
    {
        return $this->aiCredentials?->toArray();
    }

    /**
     * Get MCP credentials as array for N8NService
     */
    public function getMcpCredentialsArray(): ?array
    {
        return $this->mcpCredentials?->toArray();
    }

    /**
     * Get preference value by key with default
     */
    public function getPreference(string $key, mixed $default = null): mixed
    {
        return $this->preferences[$key] ?? $default;
    }

    /**
     * Set a preference value
     */
    public function setPreference(string $key, mixed $value): self
    {
        $this->preferences = $this->preferences ?? [];
        $this->preferences[$key] = $value;

        return $this;
    }
}
