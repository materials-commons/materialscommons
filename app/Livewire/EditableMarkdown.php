<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;

class EditableMarkdown extends Component
{
    /**
     * The Markdown content to be displayed and edited.
     *
     * @var string
     */
    public string $content = '';

    /**
     * The original content before editing.
     *
     * @var string
     */
    protected string $originalContent = '';

    /**
     * The content being edited in the textarea.
     *
     * @var string
     */
    public string $editContent = '';

    /**
     * Whether the component is in edit mode.
     *
     * @var bool
     */
    public bool $isEditing = false;

    /**
     * Whether to show the preview panel.
     *
     * @var bool
     */
    public bool $showPreview = true;

    /**
     * Event that will be emitted when content is saved.
     *
     * @var string
     */
    public string $saveEvent = 'markdownSaved';

    /**
     * Mount the component.
     *
     * @param string $content The initial Markdown content
     * @param string $saveEvent The event to emit when content is saved
     * @return void
     */
    public function mount(string $content = '', string $saveEvent = 'markdownSaved')
    {
        $this->content = $content;
        $this->originalContent = $content;
        $this->editContent = $content;
        $this->saveEvent = $saveEvent;
    }

    /**
     * Toggle edit mode.
     *
     * @return void
     */
    public function toggleEdit()
    {
        ray('toggleEdit called');
        $this->isEditing = !$this->isEditing;

        if ($this->isEditing) {
            // When entering edit mode, set the edit content to the current content
            $this->editContent = $this->content;
        }
    }

    /**
     * Toggle preview panel.
     *
     * @return void
     */
    public function togglePreview()
    {
        $this->showPreview = !$this->showPreview;
    }

    /**
     * Save the edited content.
     *
     * @return void
     */
    public function save()
    {
        $this->content = $this->editContent;
        $this->originalContent = $this->content;
        $this->isEditing = false;

        // Emit an event so parent components can react to the save
        $this->dispatch($this->saveEvent, content: $this->content);
    }

    /**
     * Cancel editing and revert to the original content.
     *
     * @return void
     */
    public function cancel()
    {
        $this->editContent = $this->originalContent;
        $this->isEditing = false;
    }

    /**
     * Get the preview content.
     *
     * @return string
     */
    #[Computed]
    public function previewContent()
    {
        return $this->editContent;
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.editable-markdown');
    }
}
