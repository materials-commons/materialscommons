<?php

namespace App\Actions\Projects;

use App\DTO\HealthReport;
use App\Models\File;
use App\Models\Project;
use App\Traits\Files\FileHealth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CreateProjectHealthReportAction
{
    use FileHealth;

    private HealthReport $healthReport;

    public function __construct(Project $project)
    {
        $this->healthReport = new HealthReport($project);
    }

    public function execute(): void
    {
        $this->checkProjectFilesHealth();
        $this->checkForUnprocessedGlobusUploads();
        $this->checkForOldGlobusDownloads();
        $this->checkForUnpublishedDatasetsWithDOIs();
        $this->checkPublishedDatasetsHealth();
        $this->checkExperimentsHealth();
        $this->createReport();
        $this->setProjectHealthStatus();
    }

    private function checkProjectFilesHealth(): void
    {
        $this->checkProjectFileExistence();
        $this->checkForDuplicateCurrentFiles();
    }

    private function checkProjectFileExistence(): void
    {
        $query = File::with(['directory'])
                     ->where('mime_type', '<>', 'directory')
                     ->where('project_id', $this->healthReport->project->id);
        foreach ($query->cursor() as $file) {
            if (!$file->isFile()) {
                continue;
            }

            if (!$file->realFileExists()) {
                $this->setFileHealthMissing($file, 'create-project-health-report:existence-check');
                $this->healthReport->missingFiles->push($file);
            }
        }
    }

    private function checkForDuplicateCurrentFiles(): void
    {
        $projectId = $this->healthReport->project->id;
        $q = File::whereExists(function ($query) use ($projectId) {
            $query
                ->select(DB::raw(1))
                ->from("files as f2")
                ->whereRaw("f2.project_id = files.project_id")
                ->whereRaw("f2.directory_id = files.directory_id")
                ->whereRaw("f2.name = files.name")
                ->where("f2.project_id", $projectId)
                ->where("f2.current", true)
                ->whereNotNull("f2.directory_id")
                ->havingRaw("COUNT(*) > 1");
        });

        $this->healthReport->multipleCurrentFiles = $q->where("project_id", $projectId)
                                                      ->where("current", true)
                                                      ->whereNotNull("directory_id")
                                                      ->orderBy("directory_id")
                                                      ->orderBy("name")
                                                      ->get();
    }

    private function checkForUnprocessedGlobusUploads(): void
    {

    }

    private function checkForOldGlobusDownloads(): void
    {

    }

    private function checkForUnpublishedDatasetsWithDOIs(): void
    {
    }

    private function checkPublishedDatasetsHealth(): void
    {

    }

    private function checkExperimentsHealth(): void
    {

    }

    private function createReport(): void
    {
        $this->healthReport->saveToStorage();

    }

    private function setProjectHealthStatus()
    {
        $projectHealthStatus = "healthy";
        if ($this->healthReport->missingFiles->count() > 0) {
            $projectHealthStatus = "critical";
        } elseif ($this->healthReport->multipleCurrentFiles->count() > 0) {
            $projectHealthStatus = "warning";
        } elseif ($this->healthReport->unpublishedDatasetsWithDOIs->count() > 0) {
            $projectHealthStatus = "warning";
        }
        $this->healthReport->project->update(['health' => $projectHealthStatus]);
    }

}
