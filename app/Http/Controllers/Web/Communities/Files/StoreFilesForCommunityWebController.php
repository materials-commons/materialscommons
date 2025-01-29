<?php

namespace App\Http\Controllers\Web\Communities\Files;

use App\Actions\Files\CreateFilesForCommunityAction;
use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

class StoreFilesForCommunityWebController extends Controller
{
    public function __invoke(
        Request $request,
        CreateFilesForCommunityAction $createFilesForCommunityAction,
        Community $community
    ) {
        $validated = $request->validate([
            'files.*' => 'nullable|file',
            'file'    => 'nullable|file',
        ]);

        if (isset($validated['files'])) {
            $files = $validated['files'];
        } else {
            $files = [$validated['file']];
        }

        $createFilesForCommunityAction->execute($community, $files);
    }
}
