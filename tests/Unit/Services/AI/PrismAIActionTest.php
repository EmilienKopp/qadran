<?php

namespace Tests\Unit\Services\AI;

use App\Services\AI\Actions\PrismAIAction;
use Tests\TestCase;

class PrismAIActionTest extends TestCase
{
    public function test_text_to_command_throws_exception()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('not implemented in PrismAIAction');

        $action = new PrismAIAction();
        $action->textToCommand('system prompt', 'user text');
    }
}
