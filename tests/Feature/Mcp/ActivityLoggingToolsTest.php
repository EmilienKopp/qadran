<?php

namespace Tests\Feature\Mcp;

use App\Mcp\Servers\QadranServer;
use App\Mcp\Tools\CreateActivity;
use App\Mcp\Tools\CreateActivityBatch;
use App\Mcp\Tools\ListActivityTypes;
use Laravel\Mcp\Server\Transport\FakeTransporter;
use Tests\TestCase;

class ActivityLoggingToolsTest extends TestCase
{
    public function test_create_activity_tool_exists(): void
    {
        $transporter = new FakeTransporter;
        $server = new QadranServer($transporter);

        $reflection = new \ReflectionClass($server);
        $toolsProperty = $reflection->getProperty('tools');
        $toolsProperty->setAccessible(true);
        $tools = $toolsProperty->getValue($server);

        $this->assertContains(CreateActivity::class, $tools);
    }

    public function test_create_activity_batch_tool_exists(): void
    {
        $transporter = new FakeTransporter;
        $server = new QadranServer($transporter);

        $reflection = new \ReflectionClass($server);
        $toolsProperty = $reflection->getProperty('tools');
        $toolsProperty->setAccessible(true);
        $tools = $toolsProperty->getValue($server);

        $this->assertContains(CreateActivityBatch::class, $tools);
    }

    public function test_list_activity_types_tool_exists(): void
    {
        $transporter = new FakeTransporter;
        $server = new QadranServer($transporter);

        $reflection = new \ReflectionClass($server);
        $toolsProperty = $reflection->getProperty('tools');
        $toolsProperty->setAccessible(true);
        $tools = $toolsProperty->getValue($server);

        $this->assertContains(ListActivityTypes::class, $tools);
    }

    public function test_create_activity_tool_can_be_instantiated(): void
    {
        $tool = new CreateActivity;
        $this->assertInstanceOf(CreateActivity::class, $tool);
    }

    public function test_create_activity_batch_tool_can_be_instantiated(): void
    {
        $tool = new CreateActivityBatch;
        $this->assertInstanceOf(CreateActivityBatch::class, $tool);
    }

    public function test_list_activity_types_tool_can_be_instantiated(): void
    {
        $tool = new ListActivityTypes;
        $this->assertInstanceOf(ListActivityTypes::class, $tool);
    }
}
