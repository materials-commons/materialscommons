<?php

namespace App\Http\Controllers\Web\Published\Files;

use App\Actions\Files\GetFileContentsForDisplayAction;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\File;
use App\Traits\FileType;
use App\ViewModels\Files\ShowFileViewModel;
use Illuminate\Support\Facades\Response;
use function abort_if;
use function is_null;
use function view;

class DisplayPublishedFileWebController extends Controller
{
    use FileType;
    public function __invoke(GetFileContentsForDisplayAction $getFileContentsForDisplayAction, Dataset $dataset, File $file)
    {

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
