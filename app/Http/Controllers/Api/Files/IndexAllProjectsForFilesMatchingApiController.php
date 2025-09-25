<?php /** @noinspection PhpVoidFunctionResultUsedInspection */

namespace App\Http\Controllers\Api\Files;

use App\Http\Controllers\Controller;
use App\Http\Requests\Files\FilesMatchingRequest;
use App\Models\File;
use Illuminate\Support\Facades\DB;

class IndexAllProjectsForFilesMatchingApiController extends Controller
{
    public function __invoke(FilesMatchingRequest $request)
    {
        $userId = auth()->id();
        $validated = $request->validated();
        $matches = $validated['match'];
        return File::with(['directory', 'owner'])
                   ->whereIn('project_id',
                       DB::table('projects')
                         ->select('id')
                         ->whereIn('team_id', DB::table('team2member')
                                                ->select('team_id')
                                                ->where('user_id', $userId)
                                                ->union(DB::table('team2admin')
                                                          ->select('team_id')
                                                          ->where('user_id', $userId))
                         )
                   )
                   ->where(function ($query) use ($matches) {
                       for ($i = 0; $i < count($matches); $i++) {
                           $query->orWhere('name', 'like', $matches[$i]);
                       }
                   })
                   ->active()
                   ->jsonPaginate();

    }
}
