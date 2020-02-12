<?php

namespace App\Http\Controllers\Web\Files;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class DownloadFileWebController extends Controller
{
    public function __invoke(Project $project, File $file)
    {
        $path = $this->storagePathForFile($file);
        if ( ! FileFacade::exists($path)) {
            abort(404);
        }

        $f = FileFacade::get($path);
        $type = FileFacade::mimeType($path);
        $response = Response::make($f, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    private function storagePathForFile($file)
    {
        $uuid = $file->uuid;
        if ($file->uses_uuid !== null) {
            $uuid = $file->uses_uuid;
        }
        $entries = explode('-', $uuid);
        $entry1 = $entries[1];

        return Storage::disk('mcfs')->path("{$entry1[0]}{$entry1[1]}/{$entry1[2]}{$entry1[3]}/{$uuid}");
    }
}
