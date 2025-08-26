<?php

namespace App\Livewire\Projects;

use App\Models\File;
use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\On;

class FileViewer extends Component
{
    public ?File $selectedFile = null;
    public Project $project;

    public function mount(Project $project, ?File $file = null)
    {
        $this->project = $project;
        ray("mount called", $file);
        $this->selectedFile = $file;
    }

    #[On('selectFile')]
    public function selectFile($fileId)
    {
        ray("selectedFile called");
        $this->selectedFile = File::find($fileId);
    }

    public function clearSelection()
    {
        $this->selectedFile = null;
    }

    public function render()
    {
        return view('livewire.projects.file-viewer');
    }
}
