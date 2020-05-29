<?php

namespace App\Http\Controllers\Web\Communities\Files;

use App\Http\Controllers\Controller;
use App\Http\Requests\Files\UpdateFileRequest;
use App\Models\Community;
use App\Models\File;

class UpdateFileForCommunityWebController extends Controller
{
    use FileInCommunity;

    public function __invoke(UpdateFileRequest $request, Community $community, File $file)
    {
        abort_unless($this->fileInCommunity($community, $file), 404, "No such file in community");

        $validated = $request->validated();
        $file->update($validated);
        return redirect(route('communities.files.show', [$community, $file]));
    }
}
