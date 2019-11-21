<?php

namespace Tests\Unit\Actions\Workflows;

use App\Actions\Workflows\ParseWorkflowAction;
use Tests\TestCase;

class ParseWorkflowActionTest extends TestCase
{
    /** @test */
    public function it_parses_with_each_item_on_separate_line()
    {
        $line = <<< 'WORKFLOW'
start=>operation: start
finished=>operation: finished
start->finished

WORKFLOW;

        $parseWorkflowAction = new ParseWorkflowAction();
        $entries = $parseWorkflowAction($line);
        $this->assertEquals(3, sizeof($entries));
        $this->assertEquals(2, sizeof($entries['operations']));
        $this->assertEquals('start', $entries['operations'][0]);
        $this->assertEquals('finished', $entries['operations'][1]);
        $this->assertEquals(1, sizeof($entries['steps']));
        $this->assertEquals('start->finished', $entries['steps'][0]);
    }
}
