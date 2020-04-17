<?php

namespace App\Http\Controllers\Web\Files;

use App\Actions\Files\GetFileContentsForDisplayAction;
use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Support\Facades\Response;

class DisplayFileWebController extends Controller
{
    public function __invoke(GetFileContentsForDisplayAction $getFileContentsForDisplayAction, Project $project,
        File $file)
    {
        $f = $getFileContentsForDisplayAction->execute($file);
        abort_if(is_null($f), 404);
        $response = Response::make($f, 200);
        $response->header("Content-Type",
            $getFileContentsForDisplayAction->getMimeTypeTakingIntoAccountConversion($file));

        return $response;
    }
}
