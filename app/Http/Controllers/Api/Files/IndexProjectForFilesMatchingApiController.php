<?php /** @noinspection PhpVoidFunctionResultUsedInspection */

namespace App\Http\Controllers\Api\Files;

use App\Http\Controllers\Controller;
use App\Http\Requests\Files\FilesMatchingRequest;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;

class IndexProjectForFilesMatchingApiController extends Controller
{

    public function __invoke(FilesMatchingRequest $request, Project $project)
    {
        $validated = $request->validated();
        $matches = $validated['match'];
        return File::with(['directory', 'owner'])
                   ->where('project_id', $project->id)
                   ->active()
                   ->where(function ($query) use ($matches) {
                       for ($i = 0; $i < count($matches); $i++) {
                           $query->orWhere('name', 'like', $matches[$i]);
                       }
                   })
                   ->jsonPaginate();
    }
}
