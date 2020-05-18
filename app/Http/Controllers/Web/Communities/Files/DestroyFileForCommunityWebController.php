<?php

namespace App\Http\Controllers\Web\Communities\Files;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DestroyFileForCommunityWebController extends Controller
{
    use FileInCommunity;

    public function __invoke(Request $request, Community $community, File $file)
    {
        abort_unless($this->fileInCommunity($community, $file), 404, "No such file in community");
        DB::transaction(function () use ($community, $file) {
            $community->files()->toggle($file->id);
            $file->delete();
        });
        return redirect(route('communities.files.edit', [$community]));
    }
}
