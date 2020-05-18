<?php

namespace App\Http\Controllers\Web\Communities\Files;

use App\Actions\Files\GetFileContentsForDisplayAction;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\File;
use Illuminate\Support\Facades\Response;

class DisplayCommunityFileWebController extends Controller
{
    use FileInCommunity;

    public function __invoke(GetFileContentsForDisplayAction $getFileContentsForDisplayAction, Community $community,
        File $file)
    {
        abort_unless($this->fileInCommunity($community, $file), 404, "No such file in community");

        $f = $getFileContentsForDisplayAction->execute($file);
        abort_if(is_null($f), 404, "No such file in community");

        $response = Response::make($f, 200);
        $response->header("Content-Type",
            $getFileContentsForDisplayAction->getMimeTypeTakingIntoAccountConversion($file));

        return $response;
    }
}
