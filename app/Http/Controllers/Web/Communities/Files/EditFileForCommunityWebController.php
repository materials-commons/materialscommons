<?php

namespace App\Http\Controllers\Web\Communities\Files;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\File;
use App\ViewModels\Files\ShowCommunityFileViewModel;

class EditFileForCommunityWebController extends Controller
{
    use FileInCommunity;

    public function __invoke(Community $community, File $file)
    {
        abort_unless($this->fileInCommunity($community, $file), 404, "No such file in community");

        return view('app.communities.files.edit', new ShowCommunityFileViewModel($file, $community));
    }
}
