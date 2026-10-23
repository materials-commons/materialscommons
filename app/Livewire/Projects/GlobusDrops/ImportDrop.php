<?php

namespace App\Livewire\Projects\GlobusDrops;

use App\Actions\Globus\GlobusApi;
use App\Jobs\Globus\ImportGlobusDropFilesJob;
use App\Models\File;
use App\Models\GlobusDrop;
use App\Models\Project;
use App\Traits\CreateDirectories;
use App\Traits\Files\ImportFiles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ImportDrop extends Component
{
    use CreateDirectories;
    use ImportFiles;

    public Project $project;

    public GlobusDrop $drop;

    public string $destinationMode = 'existing';

    public ?int $directoryId = null;

    public string $directoryPath = '/';

    public function mount(Project $project, GlobusDrop $drop): void
    {
        abort_unless($drop->project_id === $project->id, 404);

        $this->project = $project;
        $this->drop = $drop;

        $this->directoryId = $this->directories()->first()?->id;
    }

    protected function rules(): array
    {
        return [
            'destinationMode' => ['required', 'in:existing,new'],
            'directoryId'     => [
                'nullable',
                'required_if:destinationMode,existing',
                'integer',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($this->destinationMode !== 'existing') {
                        return;
                    }

                    $exists = File::where('project_id', $this->project->id)
                                  ->where('id', $value)
                                  ->directories()
                                  ->active()
                                  ->exists();

                    if (!$exists) {
                        $fail('Please select a valid project directory.');
                    }
                },
            ],
            'directoryPath'   => [
                'nullable',
                'required_if:destinationMode,new',
                'string',
                'max:1024',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if ($this->destinationMode !== 'new') {
                        return;
                    }

                    if (!Str::startsWith($value, '/')) {
                        $fail('Directory paths must start with /.');
                    }

                    if (Str::contains($value, ['..', '//'])) {
                        $fail('Directory path contains invalid path segments.');
                    }
                },
            ],
        ];
    }

    public function import()
    {
        $this->validate();

        $destinationDirectory = $this->resolveDestinationDirectory();

        if ($destinationDirectory === null) {
            $this->addError('directoryPath', 'Unable to create or find the destination directory.');
            return null;
        }

        $dropPath = $this->dropStoragePath();

        if (!is_dir($dropPath)) {
            $this->addError('drop', 'The drop directory does not exist or has no files to import.');
            return null;
        }

        $this->importDropFiles($destinationDirectory);

        $this->drop->update([
            'directory_id' => $destinationDirectory->id,
        ]);

        return redirect()
            ->route('projects.globus.drops.index', [$this->project])
            ->with('success', 'Drop import started.');
    }

    private function resolveDestinationDirectory(): ?File
    {
        if ($this->destinationMode === 'existing') {
            return File::where('project_id', $this->project->id)
                       ->where('id', $this->directoryId)
                       ->directories()
                       ->active()
                       ->first();
        }

        $normalizedPath = $this->normalizeDirectoryPath($this->directoryPath);

        $existingDirectory = File::where('project_id', $this->project->id)
                                 ->where('path', $normalizedPath)
                                 ->directories()
                                 ->active()
                                 ->first();

        if ($existingDirectory !== null) {
            return $existingDirectory;
        }

        $rootDirectory = File::where('project_id', $this->project->id)
                             ->where('path', '/')
                             ->directories()
                             ->active()
                             ->first();

        if ($rootDirectory === null) {
            return null;
        }

        $this->makeDirPathInProject($rootDirectory, $normalizedPath, $this->project);

        return File::where('project_id', $this->project->id)
                   ->where('path', $normalizedPath)
                   ->directories()
                   ->active()
                   ->first();
    }

    private function importDropFiles(File $destinationDirectory): void
    {
        $globusApi = GlobusApi::createGlobusApi();
        $globusApi->deleteACLsOnPath($this->drop->globus_endpoint_id, $this->drop->globus_path);
        $this->drop->update(['status' => 'importing']);
        ImportGlobusDropFilesJob::dispatch($this->drop, $destinationDirectory->path)->onQueue('globus');
    }

    private function normalizeDirectoryPath(string $path): string
    {
        $path = trim($path);

        if ($path === '') {
            return '/';
        }

        $path = '/'.trim($path, '/');

        if ($path === '//') {
            return '/';
        }

        return $path;
    }

    private function dropStorageLocation(): string
    {
        return "__globus_uploads/drops/{$this->drop->id}/{$this->drop->name}";
    }

    private function dropStoragePath(): string
    {
        return Storage::disk('mcfs')->path($this->dropStorageLocation());
    }

    public function directories()
    {
        return File::where('project_id', $this->project->id)
                   ->directories()
                   ->active()
                   ->orderBy('path')
                   ->get();
    }

    public function render()
    {
        return view('livewire.projects.globus-drops.import-drop', [
            'directories' => $this->directories(),
        ]);
    }
}
