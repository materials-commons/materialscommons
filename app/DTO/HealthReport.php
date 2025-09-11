<?php

namespace App\DTO;

use App\Models\File;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use JsonSerializable;
use Livewire\Wireable;

class HealthReport implements JsonSerializable, Wireable
{
    public Project $project;
    public Collection $missingFiles;
    public Collection $oldGlobusDownloads;
    public Collection $unpublishedDatasetsWithDOIs;
    public Collection $unprocessedGlobusUploads;

    public Collection $multipleCurrentFiles;

    public function __construct(Project $project)
    {
        $this->project = $project;
        $this->missingFiles = collect();
        $this->oldGlobusDownloads = collect();
        $this->unpublishedDatasetsWithDOIs = collect();
        $this->unprocessedGlobusUploads = collect();
        $this->multipleCurrentFiles = collect();
    }

    public function jsonSerialize(): array
    {
        return [
            'project_id' => $this->project->id,
            'project_uuid' => $this->project->uuid,
            'project_name' => $this->project->name,
            'missing_files' => $this->missingFiles->pluck('id')->toArray(),
            'multiple_current_files' => $this->multipleCurrentFiles->pluck('id')->toArray(),
            'old_globus_downloads' => $this->oldGlobusDownloads->pluck('id')->toArray(),
            'unpublished_datasets_with_dois' => $this->unpublishedDatasetsWithDOIs->pluck('id')->toArray(),
            'unprocessed_globus_uploads' => $this->unprocessedGlobusUploads->pluck('id')->toArray(),
        ];
    }

    public static function fromArray(array $data): self
    {
        // Load the project from the database
        $project = Project::find($data['project_id']);

        $healthReport = new self($project);
        $healthReport->missingFiles = File::with('directory')->whereIn('id', $data['missing_files'] ?? [])->get();
        $healthReport->multipleCurrentFiles = File::whereIn('id', $data['multiple_current_files'] ?? [])->get();
        $healthReport->oldGlobusDownloads = collect($data['old_globus_downloads'] ?? []);
        $healthReport->unpublishedDatasetsWithDOIs = collect($data['unpublished_datasets_with_dois'] ?? []);
        $healthReport->unprocessedGlobusUploads = collect($data['unprocessed_globus_uploads'] ?? []);

        return $healthReport;
    }

    public function saveToStorage(): bool
    {
        $folderPathPartial = self::getStorageFolderPathPartial($this->project);
        if (!Storage::disk('mcfs')->makeDirectory($folderPathPartial)) {
            return false;
        }

        $date = Carbon::now()->format('Y-m-d');
        $filename = "{$date}.json";
        $filePath = $folderPathPartial . $filename;

        $json = json_encode($this, JSON_PRETTY_PRINT);
        return Storage::disk('mcfs')->put($filePath, $json);
    }

    public static function readFromStorage($date, Project $project): ?HealthReport
    {
        $filePath = self::getStorageFolderPathPartial($project) . $date . '.json';
        if (!Storage::disk('mcfs')->exists($filePath)) {
            return null;
        }
        $json = Storage::disk('mcfs')->get($filePath);
        return self::fromArray(json_decode($json, true));
    }

    public static function getStorageFolderPathPartial(Project $project): string
    {
        return "__health-reports/{$project->id}/";
    }

    public function toLivewire()
    {
        return $this->jsonSerialize();
    }

    public static function fromLivewire($value)
    {
       return self::fromArray($value);
    }
}
