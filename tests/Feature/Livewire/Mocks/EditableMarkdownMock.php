<?php

namespace Tests\Feature\Livewire\Mocks;

use App\Livewire\EditableMarkdown;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;

class EditableMarkdownMock extends EditableMarkdown
{
    /**
     * Override the render method to avoid rendering the view with x-markdown component
     * This is used for testing only
     *
     * @return string
     */
    public function render(): string
    {
        // Return a simple string instead of rendering the view
        return '<div>Mock EditableMarkdown Component</div>';
    }
}
