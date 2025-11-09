<?php

namespace App\Services;

use App\DTOs\N8nConfig;
use App\Models\Landlord\Tenant;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class N8NService
{
    protected $apiUrl;

    protected $apiKey;

    public function __construct(
        public Client $httpClient
    ) {
        $this->apiUrl = config('services.n8n.api_url');
        $this->apiKey = config('services.n8n.api_key');
    }

    public function getTenantLevelConfig()
    {
        return Tenant::current()?->n8n_config ?? new N8nConfig;
    }

    public function getUserLevelConfig()
    {
        return auth('tenant')->user()?->n8n_config ?? new N8nConfig;
    }

    public function getMergedConfig()
    {
        return $this->getTenantLevelConfig()->mergeWith($this->getUserLevelConfig());
    }

    public function createMcpCredentials(string $name, array $credentials)
    {
        $response = $this->httpClient->post("{$this->apiUrl}/credentials", [
            'headers' => [
                'X-N8N-API-KEY' => $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'name' => $name,
                'type' => 'httpBearerAuth',
                'data' => $credentials,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function createWorkflow(array $workflowData)
    {
        $tenantHost = Tenant::current()->host;
        $userIdentifier = auth('tenant')->user()?->handle ?? auth('tenant')->user()?->id;
        $name = Str::lower("voice-agent::{$tenantHost}::{$userIdentifier}");

        $json = array_merge([
            'name' => $name,
        ],
            $workflowData,
            $this->getAgentSettings()
        );

        $response = $this->httpClient->post("{$this->apiUrl}/workflows", [
            'headers' => [
                'X-N8N-API-KEY' => $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => $json,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function activateWorkflow(string $workflowId)
    {
        $response = $this->httpClient->post("{$this->apiUrl}/workflows/{$workflowId}/activate", [
            'headers' => [
                'X-N8N-API-KEY' => $this->apiKey,
                'Content-Type' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function deleteMcpCredentials(string $credentialId): bool
    {
        try {
            $response = $this->httpClient->delete("{$this->apiUrl}/credentials/{$credentialId}", [
                'headers' => [
                    'X-N8N-API-KEY' => $this->apiKey,
                ],
            ]);

            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            \Log::error('Failed to delete n8n MCP credentials', [
                'credential_id' => $credentialId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Generate an AI Agent workflow template with MCP integration
     *
     * @param  array  $mcpCredentials  MCP Bearer token credentials ['id' => '...', 'name' => '...']
     * @param  string  $mcpEndpointUrl  Full URL to the MCP endpoint (e.g., 'https://acme.com/mcp/qadran')
     * @param  array|null  $aiCredentials  Optional AI credentials ['id' => '...', 'name' => '...'], defaults to template values
     * @param  string|null  $appUrl  Optional APP_URL for decrypt-tenant endpoint, defaults to config('app.url')
     * @return array The workflow template ready to be created with 'workflow' and 'webhookId' keys
     */
    public function generateAgentWorkflow(
        array $mcpCredentials,
        string $mcpEndpointUrl,
        ?array $aiCredentials = null,
        ?string $appUrl = null
    ): array {
        $nodeIds = [
            'aiAgent' => $this->generateUuid(),
            'anthropicModel' => $this->generateUuid(),
            'webhook' => $this->generateUuid(),
            'mcpClient' => $this->generateUuid(),
            'serverAuth' => $this->generateUuid(),
            'respondWebhook' => $this->generateUuid(),
        ];

        $webhookId = $this->generateUuid();

        $baseUrl = $appUrl ?? config('app.url');
        if (Str::contains($baseUrl, 'localhost')) {
            $baseUrl = 'http://host.docker.internal:'.parse_url($baseUrl, PHP_URL_PORT);
        }

        if (! $aiCredentials) {
            $aiCredentials = [
                'id' => config('services.n8n.ai_credential_id'),
                'name' => config('services.n8n.ai_credential_name'),
            ];
        }

        return [

            'nodes' => [
                [
                    'parameters' => [
                        'promptType' => 'define',
                        'text' => '={{ $("Webhook").item.json.body.system_prompt }}',
                        'options' => new \stdClass,
                    ],
                    'type' => '@n8n/n8n-nodes-langchain.agent',
                    'typeVersion' => 2.2,
                    'position' => [-384, -48],
                    'id' => $nodeIds['aiAgent'],
                    'name' => 'AI Agent',
                ],
                [
                    'parameters' => [
                        'model' => [
                            '__rl' => true,
                            'mode' => 'list',
                            'value' => 'claude-sonnet-4-20250514',
                            'cachedResultName' => 'Claude 4 Sonnet',
                        ],
                        'options' => new \stdClass,
                    ],
                    'type' => '@n8n/n8n-nodes-langchain.lmChatAnthropic',
                    'typeVersion' => 1.3,
                    'position' => [-384, 256],
                    'id' => $nodeIds['anthropicModel'],
                    'name' => 'Anthropic Chat Model',
                    'credentials' => [
                        'anthropicApi' => $aiCredentials,
                    ],
                ],
                [
                    'parameters' => [
                        'httpMethod' => 'POST',
                        'path' => $webhookId,
                        'responseMode' => 'responseNode',
                        'options' => new \stdClass,
                    ],
                    'type' => 'n8n-nodes-base.webhook',
                    'typeVersion' => 2.1,
                    'position' => [-1008, -48],
                    'id' => $nodeIds['webhook'],
                    'name' => 'Webhook',
                    'webhookId' => $webhookId,
                ],
                [
                    'parameters' => [
                        'endpointUrl' => $mcpEndpointUrl,
                        'serverTransport' => 'httpStreamable',
                        'authentication' => 'bearerAuth',
                        'options' => new \stdClass,
                    ],
                    'type' => '@n8n/n8n-nodes-langchain.mcpClientTool',
                    'typeVersion' => 1.1,
                    'position' => [-240, 256],
                    'id' => $nodeIds['mcpClient'],
                    'name' => 'MCP Client',
                    'credentials' => [
                        'httpBearerAuth' => $mcpCredentials,
                    ],
                ],
                [
                    'parameters' => [
                        'method' => 'POST',
                        'url' => "{$baseUrl}/api/n8n/decrypt-tenant",
                        'sendHeaders' => true,
                        'headerParameters' => [
                            'parameters' => [
                                [
                                    'name' => 'X-N8N-Secret',
                                    'value' => config('services.n8n.secret'),
                                ],
                                [
                                    'name' => 'Authorization',
                                    'value' => '=Bearer {{ $json.body.token }}',
                                ],
                            ],
                        ],
                        'sendBody' => true,
                        'bodyParameters' => [
                            'parameters' => [
                                [
                                    'name' => 'tenant_id',
                                    'value' => '={{ $json.body.tenant_id }}',
                                ],
                                [
                                    'name' => 'token',
                                    'value' => '={{ $json.body.token }}',
                                ],
                            ],
                        ],
                        'options' => new \stdClass,
                    ],
                    'type' => 'n8n-nodes-base.httpRequest',
                    'typeVersion' => 4.2,
                    'position' => [-704, -48],
                    'id' => $nodeIds['serverAuth'],
                    'name' => 'Server Auth',
                ],
                [
                    'parameters' => [
                        'respondWith' => 'allIncomingItems',
                        'options' => new \stdClass,
                    ],
                    'type' => 'n8n-nodes-base.respondToWebhook',
                    'typeVersion' => 1.4,
                    'position' => [-16, -48],
                    'id' => $nodeIds['respondWebhook'],
                    'name' => 'Respond to Webhook',
                ],
            ],
            'connections' => [
                'AI Agent' => [
                    'main' => [
                        [
                            [
                                'node' => 'Respond to Webhook',
                                'type' => 'main',
                                'index' => 0,
                            ],
                        ],
                    ],
                ],
                'Anthropic Chat Model' => [
                    'ai_languageModel' => [
                        [
                            [
                                'node' => 'AI Agent',
                                'type' => 'ai_languageModel',
                                'index' => 0,
                            ],
                        ],
                    ],
                ],
                'Webhook' => [
                    'main' => [
                        [
                            [
                                'node' => 'Server Auth',
                                'type' => 'main',
                                'index' => 0,
                            ],
                        ],
                    ],
                ],
                'MCP Client' => [
                    'ai_tool' => [
                        [
                            [
                                'node' => 'AI Agent',
                                'type' => 'ai_tool',
                                'index' => 0,
                            ],
                        ],
                    ],
                ],
                'Server Auth' => [
                    'main' => [
                        [
                            [
                                'node' => 'AI Agent',
                                'type' => 'main',
                                'index' => 0,
                            ],
                        ],
                    ],
                ],
            ],
            'webhookId' => $webhookId,
        ];
    }

    public function getAgentSettings()
    {
        return [
            'settings' => [
                'saveExecutionProgress' => false,
                'saveManualExecutions' => false,
                'saveDataErrorExecution' => 'all',
                'saveDataSuccessExecution' => 'all',
                'executionTimeout' => 3600,
                'timezone' => timezone(),
                'executionOrder' => 'v1',
            ],
        ];
    }

    /**
     * Generate a random UUID v4
     */
    private function generateUuid(): string
    {
        return (string) Str::uuid();
    }

    /**
     * Delete a workflow from n8n
     *
     * @param  string  $workflowId  The workflow ID to delete
     * @return bool True if successful
     */
    public function deleteWorkflow(string $workflowId): bool
    {
        try {
            $response = $this->httpClient->delete("{$this->apiUrl}/workflows/{$workflowId}", [
                'headers' => [
                    'X-N8N-API-KEY' => $this->apiKey,
                ],
            ]);

            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            \Log::error('Failed to delete n8n workflow', [
                'workflow_id' => $workflowId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
