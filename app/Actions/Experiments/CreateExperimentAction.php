<?php

namespace App\Actions\Experiments;

use App\Enums\ExperimentStatus;
use App\Helpers\PathHelpers;
use App\Jobs\Etl\ProcessSpreadsheetJob;
use App\Models\Experiment;
use App\Models\File;
use Illuminate\Support\Str;
use function array_key_exists;
use function dirname;
use function parse_url;
use const PHP_URL_HOST;
use const PHP_URL_PATH;

class CreateExperimentAction
{
    public function __invoke($data, $sheet = null)
    {
        $experiment = new Experiment(['name' => $data['name'], 'project_id' => $data['project_id']]);
        if (array_key_exists('description', $data)) {
            $experiment->description = $data['description'];
        }
        if (array_key_exists('summary', $data)) {
            $experiment->summary = $data['summary'];
        }

        $experiment->owner_id = auth()->id();
        $experiment->status = ExperimentStatus::InProgress;
        if (!is_null($sheet)) {
            $experiment->sheet_id = $sheet->id;
        } elseif (!is_null($data['file_id'])) {
            $file = File::with('directory')
                        ->where("id", $data["file_id"])
                        ->where("project_id", $data["project_id"])
                        ->first();
            $experiment->loaded_file_path = PathHelpers::joinPaths($file->directory->path, $file->name);
        }
        $experiment->save();
        $experiment->refresh();

        $fileId = null;
        $sheetUrl = null;
        if (array_key_exists('file_id', $data) && $data['file_id'] !== null) {
            $fileId = $data['file_id'];
        }

        if (array_key_exists('sheet_url', $data) && $data['sheet_url'] !== null) {
            $sheetUrl = $this->cleanupGoogleSheetUrl($data['sheet_url']);
        }

        if (!is_null($fileId) || !is_null($sheetUrl)) {
            $ps = new ProcessSpreadsheetJob($data['project_id'], $experiment->id, auth()->id(), $fileId, $sheetUrl);
            dispatch($ps)->onQueue('globus');
        }

        return $experiment;
    }

    private function cleanupGoogleSheetUrl($url): ?string
    {
        if (Str::contains($url, "/edit?")) {
            // Remove /edit from the end of the url
            $path = dirname(parse_url($url, PHP_URL_PATH));

            $host = parse_url($url, PHP_URL_HOST);

            // $path starts with a slash (/), so we don't add one to separate host and path when constructing the url.
            return "https://{$host}{$path}";
        }

        return $url;
    }
}
