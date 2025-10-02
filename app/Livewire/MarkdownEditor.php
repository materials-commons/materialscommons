<?php

namespace App\Livewire;

use Livewire\Component;

class MarkdownEditor extends Component
{
    /**
     * The Markdown content.
     *
     * @var string
     */
    public string $content = '';
    public string $context;
    public string $contextId;

    /**
     * The filename for downloading the markdown content.
     *
     * @var string
     */
    public string $downloadFilename = 'markdown.md';

    /**
     * Whether to show the download modal.
     *
     * @var bool
     */
    public bool $showDownloadModal = false;

    /**
     * Mount the component.
     *
     * @param string $content The initial Markdown content
     * @return void
     */
    public function mount(string $content = '', string $context = '', int $contextId = 0)
    {
        $this->content = $content;
        $this->context = '';
        $this->contextId = '';
    }

    /**
     * Handle the markdown saved event.
     *
     * @param string $content The saved markdown content
     * @return void
     */
    public function handleMarkdownSaved($content)
    {
        $this->content = $content;
        $this->toggleDownloadModal();
    }

    /**
     * Toggle download modal.
     *
     * @return void
     */
    public function toggleDownloadModal()
    {
        $this->showDownloadModal = !$this->showDownloadModal;
    }

    /**
     * Download the markdown content as a file.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download()
    {
        $this->showDownloadModal = false;

        // Make sure filename has .md extension
        if (!str_ends_with(strtolower($this->downloadFilename), '.md')) {
            $this->downloadFilename .= '.md';
        }

        return response()->streamDownload(function () {
            echo $this->content;
        }, $this->downloadFilename);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.markdown-editor');
    }
}
