<?php

namespace Tests\Feature\Mcp;

use App\Mcp\Servers\QadranServer;
use Laravel\Mcp\Server\Transport\FakeTransporter;
use Tests\TestCase;

class McpServerTest extends TestCase
{
    public function test_qadran_server_can_be_instantiated(): void
    {
        $transporter = new FakeTransporter;
        $server = new QadranServer($transporter);

        $this->assertInstanceOf(QadranServer::class, $server);
    }

    public function test_server_has_correct_configuration(): void
    {
        $transporter = new FakeTransporter;
        $server = new QadranServer($transporter);

        // Test via reflection since properties are protected
        $reflection = new \ReflectionClass($server);

        $nameProperty = $reflection->getProperty('name');
        $nameProperty->setAccessible(true);
        $this->assertEquals('Qadran Server', $nameProperty->getValue($server));

        $versionProperty = $reflection->getProperty('version');
        $versionProperty->setAccessible(true);
        $this->assertEquals('0.0.1', $versionProperty->getValue($server));
    }

    public function test_server_has_all_required_tools(): void
    {
        $transporter = new FakeTransporter;
        $server = new QadranServer($transporter);

        $reflection = new \ReflectionClass($server);
        $toolsProperty = $reflection->getProperty('tools');
        $toolsProperty->setAccessible(true);
        $tools = $toolsProperty->getValue($server);

        $expectedTools = [
            \App\Mcp\Tools\CreateProject::class,
            \App\Mcp\Tools\CreateTask::class,
            \App\Mcp\Tools\ClockIn::class,
            \App\Mcp\Tools\ClockOut::class,
            \App\Mcp\Tools\GenerateReport::class,
            \App\Mcp\Tools\ListTasks::class,
            \App\Mcp\Tools\ListProjects::class,
            \App\Mcp\Tools\CreateActivity::class,
            \App\Mcp\Tools\CreateActivityBatch::class,
            \App\Mcp\Tools\ListActivityTypes::class,
        ];

        $this->assertCount(10, $tools);
        $this->assertEquals($expectedTools, $tools);
    }
}
