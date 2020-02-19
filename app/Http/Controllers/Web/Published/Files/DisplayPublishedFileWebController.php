<?php

namespace App\Http\Controllers\Web\Published\Files;

use App\Actions\Files\GetFileContentsForDisplayAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\File;
use Illuminate\Support\Facades\Response;

class DisplayPublishedFileWebController extends Controller
{
    public function __invoke(GetFileContentsForDisplayAction $getFileContentsForDisplayAction, Dataset $dataset, File $file)
    {
        $f = $getFileContentsForDisplayAction->execute($file);
        abort_if(is_null($f), 404);
        $response = Response::make($f, 200);
        $response->header("Content-Type", $getFileContentsForDisplayAction->getMimeTypeTakingIntoAccountConversion($file));

        return $response;
    }
}
