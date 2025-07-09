<?php

namespace App\Http\Controllers\Web\Published\Files;

use App\Actions\Files\GetFileContentsForDisplayAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\File;
use App\Traits\FileType;
use App\ViewModels\Files\ShowFileViewModel;
use Illuminate\Support\Facades\Response;
use function abort;
use function abort_if;
use function auth;
use function is_null;
use function view;

class DisplayPublishedFileWebController extends Controller
{
    // Files that are 5KB less than 100MB (to give some room) can be displayed if the user is
    // not logged in. This prevents us from loading files from tape storage.
    private const MAX_DISPLAY_FILE_SIZE = 104852480; // 100 MB in bytes (1024 * 1024 * 100)-5*1024

    use FileType;
    public function __invoke(GetFileContentsForDisplayAction $getFileContentsForDisplayAction, Dataset $dataset, File $file)
    {
        if (!auth()->check()) {
            if ($file->size < self::MAX_DISPLAY_FILE_SIZE) {
                if ($file->isImage()) {
                    $f = $getFileContentsForDisplayAction->execute($file);
                    abort_if(is_null($f), 404);
                    $response = Response::make($f, 200);
                    $response->header("Content-Type",
                        $getFileContentsForDisplayAction->getMimeTypeTakingIntoAccountConversion($file));

                    return $response;
                }
            }
            abort(403);
        }

        if ($this->fileTypeShouldReturnContents($file)) {
            $f = $getFileContentsForDisplayAction->execute($file);
            abort_if(is_null($f), 404);
            $response = Response::make($f, 200);
            $response->header("Content-Type",
                $getFileContentsForDisplayAction->getMimeTypeTakingIntoAccountConversion($file));

            return $response;
        }

        $viewModel = new ShowFileViewModel($file, null, $dataset);
        return view('app.files.display', $viewModel);
    }
}
