<?php

namespace App\Livewire\Folders;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class FolderZipProgress extends Component
{
    public $zipId;
    public $progress = 0;
    public $status = 'waiting';
    public $totalFiles = 0;
    public $processedFiles = 0;
    public $zipPath = null;
    public $showDownloadButton = false;
    public $downloadUrl = '';

    protected $listeners = ['checkZipProgress'];

    public function mount($zipId)
    {
        $this->zipId = $zipId;
    }

    public function render()
    {
        return view('livewire.folders.folder-zip-progress');
    }

    public function checkZipProgress()
    {
        $progressData = Cache::get("zip_progress_{$this->zipId}");

        if (!$progressData) {
            return;
        }

        $this->status = $progressData['status'];
        $this->progress = round($progressData['progress']);
        $this->totalFiles = $progressData['total_files'];
        $this->processedFiles = $progressData['processed_files'];
        $this->zipPath = $progressData['zip_path'];

        if ($this->status === 'completed') {
            $this->showDownloadButton = true;
            $this->downloadUrl = route('projects.folders.download-zip', [
                'zipId' => $this->zipId
            ]);
            
            // Trigger download automatically
            $this->dispatch('zipCompleted', ['downloadUrl' => $this->downloadUrl]);
        }
    }
}