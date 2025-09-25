<?php

namespace Tests\Feature\Livewire;

use Tests\Feature\Livewire\Mocks\EditableMarkdownMock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EditableMarkdownTest extends TestCase
{
    /** @test */
    public function it_initializes_with_correct_state()
    {
        $content = "# Test Markdown\n\nThis is a test.";

        Livewire::test(EditableMarkdownMock::class, ['content' => $content])
            ->assertSet('content', $content)
            ->assertSet('isEditing', false);
    }

    /** @test */
    public function it_can_toggle_edit_mode()
    {
        $content = "# Test Content";

        Livewire::test(EditableMarkdownMock::class, ['content' => $content])
            ->assertSet('isEditing', false)
            ->call('toggleEdit')
            ->assertSet('isEditing', true)
            ->assertSet('editContent', $content)
            ->call('toggleEdit')
            ->assertSet('isEditing', false);
    }

    /** @test */
    public function it_can_toggle_preview()
    {
        Livewire::test(EditableMarkdownMock::class)
            ->assertSet('showPreview', true)
            ->call('togglePreview')
            ->assertSet('showPreview', false)
            ->call('togglePreview')
            ->assertSet('showPreview', true);
    }

    /** @test */
    public function it_can_save_edited_content()
    {
        $originalContent = "# Original Content";
        $newContent = "# New Content";

        // Create a test instance without rendering the view
        $component = Livewire::test(EditableMarkdownMock::class, ['content' => $originalContent])
            ->assertSet('content', $originalContent)
            ->call('toggleEdit')
            ->set('editContent', $newContent)
            ->call('save')
            ->assertSet('content', $newContent)
            ->assertSet('isEditing', false);

        // Test that the event was dispatched
        $component->assertDispatched('markdownSaved', function ($eventName, $params) use ($newContent) {
            return $params['content'] === $newContent;
        });
    }

    /** @test */
    public function it_can_cancel_editing()
    {
        $originalContent = "# Original Content";
        $newContent = "# New Content";

        // Create a test instance without rendering the view
        Livewire::test(EditableMarkdownMock::class, ['content' => $originalContent])
            ->assertSet('content', $originalContent)
            ->call('toggleEdit')
            ->set('editContent', $newContent)
            ->call('cancel')
            // Only test that editing mode is turned off
            ->assertSet('isEditing', false)
            // And that content remains unchanged
            ->assertSet('content', $originalContent);
    }

    /** @test */
    public function it_can_use_custom_save_event_name()
    {
        $content = "# Test Content";
        $customEvent = "customSaveEvent";

        // Create a test instance without rendering the view
        $component = Livewire::test(EditableMarkdownMock::class, [
            'content' => $content,
            'saveEvent' => $customEvent
        ])
            ->assertSet('saveEvent', $customEvent)
            ->call('toggleEdit')
            ->call('save');

        // Test that the custom event was dispatched
        $component->assertDispatched($customEvent);
    }
}
