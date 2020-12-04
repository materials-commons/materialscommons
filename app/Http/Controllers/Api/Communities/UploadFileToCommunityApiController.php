<?php

namespace App\Http\Controllers\Api\Communities;

use App\Actions\Files\CreateFilesForCommunityAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Communities\CommunityResource;
use App\Models\Community;
use Illuminate\Http\Request;

class UploadFileToCommunityApiController extends Controller
{
    public function __invoke(Request $request, CreateFilesForCommunityAction $createFilesForCommunityAction,
        Community $community)
    {
        abort_unless($community->owner_id === auth()->id(), 403, "No such community");

        $validated = $request->validate([
            'files.*' => 'required|file',
        ]);

        $createFilesForCommunityAction->execute($community, $validated['files']);
        $community->load(['owner', 'datasets', 'links', 'files']);
        return new CommunityResource($community);
    }
}
