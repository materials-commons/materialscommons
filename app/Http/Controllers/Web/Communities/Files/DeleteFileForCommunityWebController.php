<?php

namespace App\Http\Controllers\Web\Communities\Files;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\File;
use Illuminate\Http\Request;

class DeleteFileForCommunityWebController extends Controller
{
    use FileInCommunity;

    public function __invoke(Request $request, Community $community, File $file)
    {
        abort_unless($this->fileInCommunity($community, $file), 404, "No such file in community");

        return view('app.communities.files.delete', [
            'community' => $community,
            'file'      => $file,
        ]);
    }
}
