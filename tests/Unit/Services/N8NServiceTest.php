<?php

namespace Tests\Unit\Services;

use App\Services\N8NService;
use GuzzleHttp\Client;
use Tests\TestCase;

class N8NServiceTest extends TestCase
{
    private N8NService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new N8NService(new Client);
    }

    public function test_generate_agent_workflow_creates_valid_structure(): void
    {
        $mcpCredentials = [
            'id' => 'test-mcp-cred-id',
            'name' => 'Test MCP Bearer',
        ];

        $mcpEndpointUrl = 'http://localhost:8000/mcp/test';

        $workflow = $this->service->generateAgentWorkflow(
            $mcpCredentials,
            $mcpEndpointUrl
        );

        // Assert basic structure
        $this->assertIsArray($workflow);
        $this->assertArrayHasKey('nodes', $workflow);
        $this->assertArrayHasKey('connections', $workflow);
        $this->assertArrayHasKey('pinData', $workflow);
        $this->assertArrayHasKey('meta', $workflow);

        // Assert 6 nodes are present
        $this->assertCount(6, $workflow['nodes']);

        // Check node names
        $nodeNames = array_column($workflow['nodes'], 'name');
        $this->assertContains('AI Agent', $nodeNames);
        $this->assertContains('Anthropic Chat Model', $nodeNames);
        $this->assertContains('Webhook', $nodeNames);
        $this->assertContains('MCP Client', $nodeNames);
        $this->assertContains('Server Auth', $nodeNames);
        $this->assertContains('Respond to Webhook', $nodeNames);
    }

    public function test_generate_agent_workflow_uses_provided_mcp_credentials(): void
    {
        $mcpCredentials = [
            'id' => 'custom-mcp-id',
            'name' => 'Custom MCP Token',
        ];

        $workflow = $this->service->generateAgentWorkflow(
            $mcpCredentials,
            'http://localhost:8000/mcp/test'
        );

        // Find MCP Client node
        $mcpNode = collect($workflow['nodes'])->firstWhere('name', 'MCP Client');

        $this->assertNotNull($mcpNode);
        $this->assertEquals($mcpCredentials, $mcpNode['credentials']['httpBearerAuth']);
    }

    public function test_generate_agent_workflow_uses_provided_mcp_endpoint(): void
    {
        $mcpEndpoint = 'https://example.com:9000/mcp/custom';

        $workflow = $this->service->generateAgentWorkflow(
            ['id' => 'test', 'name' => 'Test'],
            $mcpEndpoint
        );

        // Find MCP Client node
        $mcpNode = collect($workflow['nodes'])->firstWhere('name', 'MCP Client');

        $this->assertNotNull($mcpNode);
        $this->assertEquals($mcpEndpoint, $mcpNode['parameters']['endpointUrl']);
    }

    public function test_generate_agent_workflow_uses_custom_ai_credentials_when_provided(): void
    {
        $customAiCredentials = [
            'id' => 'custom-ai-id',
            'name' => 'Custom AI Account',
        ];

        $workflow = $this->service->generateAgentWorkflow(
            ['id' => 'test', 'name' => 'Test'],
            'http://localhost:8000/mcp/test',
            $customAiCredentials
        );

        // Find Anthropic Chat Model node
        $aiNode = collect($workflow['nodes'])->firstWhere('name', 'Anthropic Chat Model');

        $this->assertNotNull($aiNode);
        $this->assertEquals($customAiCredentials, $aiNode['credentials']['anthropicApi']);
    }

    public function test_generate_agent_workflow_uses_default_ai_credentials_when_not_provided(): void
    {
        $workflow = $this->service->generateAgentWorkflow(
            ['id' => 'test', 'name' => 'Test'],
            'http://localhost:8000/mcp/test'
        );

        // Find Anthropic Chat Model node
        $aiNode = collect($workflow['nodes'])->firstWhere('name', 'Anthropic Chat Model');

        $this->assertNotNull($aiNode);
        $this->assertArrayHasKey('anthropicApi', $aiNode['credentials']);
        $this->assertEquals('V3lx69vlrI5hqH8S', $aiNode['credentials']['anthropicApi']['id']);
    }

    public function test_generate_agent_workflow_uses_custom_app_url_when_provided(): void
    {
        $customAppUrl = 'https://custom-app.example.com';

        $workflow = $this->service->generateAgentWorkflow(
            ['id' => 'test', 'name' => 'Test'],
            'http://localhost:8000/mcp/test',
            null,
            $customAppUrl
        );

        // Find Server Auth node
        $authNode = collect($workflow['nodes'])->firstWhere('name', 'Server Auth');

        $this->assertNotNull($authNode);
        $this->assertEquals(
            "{$customAppUrl}/api/n8n/decrypt-tenant",
            $authNode['parameters']['url']
        );
    }

    public function test_generate_agent_workflow_uses_config_app_url_when_not_provided(): void
    {
        $workflow = $this->service->generateAgentWorkflow(
            ['id' => 'test', 'name' => 'Test'],
            'http://localhost:8000/mcp/test'
        );

        // Find Server Auth node
        $authNode = collect($workflow['nodes'])->firstWhere('name', 'Server Auth');

        $this->assertNotNull($authNode);
        $expectedUrl = config('app.url').'/api/n8n/decrypt-tenant';
        $this->assertEquals($expectedUrl, $authNode['parameters']['url']);
    }

    public function test_generate_agent_workflow_randomizes_node_ids(): void
    {
        $workflow1 = $this->service->generateAgentWorkflow(
            ['id' => 'test', 'name' => 'Test'],
            'http://localhost:8000/mcp/test'
        );

        $workflow2 = $this->service->generateAgentWorkflow(
            ['id' => 'test', 'name' => 'Test'],
            'http://localhost:8000/mcp/test'
        );

        // Collect all node IDs from both workflows
        $ids1 = collect($workflow1['nodes'])->pluck('id')->toArray();
        $ids2 = collect($workflow2['nodes'])->pluck('id')->toArray();

        // Assert no IDs are the same between workflows
        $this->assertEmpty(array_intersect($ids1, $ids2));
    }

    public function test_generate_agent_workflow_randomizes_webhook_id(): void
    {
        $workflow1 = $this->service->generateAgentWorkflow(
            ['id' => 'test', 'name' => 'Test'],
            'http://localhost:8000/mcp/test'
        );

        $workflow2 = $this->service->generateAgentWorkflow(
            ['id' => 'test', 'name' => 'Test'],
            'http://localhost:8000/mcp/test'
        );

        // Find webhook nodes
        $webhook1 = collect($workflow1['nodes'])->firstWhere('name', 'Webhook');
        $webhook2 = collect($workflow2['nodes'])->firstWhere('name', 'Webhook');

        // Assert webhook IDs are different
        $this->assertNotEquals($webhook1['webhookId'], $webhook2['webhookId']);
        $this->assertNotEquals($webhook1['id'], $webhook2['id']);
    }

    public function test_generate_agent_workflow_has_valid_connections(): void
    {
        $workflow = $this->service->generateAgentWorkflow(
            ['id' => 'test', 'name' => 'Test'],
            'http://localhost:8000/mcp/test'
        );

        $connections = $workflow['connections'];

        // Assert key connections exist
        $this->assertArrayHasKey('AI Agent', $connections);
        $this->assertArrayHasKey('Anthropic Chat Model', $connections);
        $this->assertArrayHasKey('Webhook', $connections);
        $this->assertArrayHasKey('MCP Client', $connections);
        $this->assertArrayHasKey('Server Auth', $connections);

        // Verify webhook connects to server auth
        $this->assertEquals('Server Auth', $connections['Webhook']['main'][0][0]['node']);

        // Verify server auth connects to AI agent
        $this->assertEquals('AI Agent', $connections['Server Auth']['main'][0][0]['node']);

        // Verify AI agent connects to respond webhook
        $this->assertEquals('Respond to Webhook', $connections['AI Agent']['main'][0][0]['node']);
    }
}
