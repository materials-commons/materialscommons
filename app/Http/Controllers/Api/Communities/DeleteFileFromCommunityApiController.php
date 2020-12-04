<?php

namespace App\Http\Controllers\Api\Communities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Communities\Files\FileInCommunity;
use App\Http\Resources\Communities\CommunityResource;
use App\Models\Community;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteFileFromCommunityApiController extends Controller
{
    use FileInCommunity;

    public function __invoke(Request $request, Community $community, File $file)
    {
        abort_unless($community->owner_id === auth()->id(), 403, "No such community");
        abort_unless($this->fileInCommunity($community, $file), 404, "No such file in community");

        DB::transaction(function () use ($community, $file) {
            $community->files()->toggle($file->id);
            $file->delete();
        });

        $community->load(['owner', 'datasets', 'links', 'files']);
        return new CommunityResource($community);
    }
}
