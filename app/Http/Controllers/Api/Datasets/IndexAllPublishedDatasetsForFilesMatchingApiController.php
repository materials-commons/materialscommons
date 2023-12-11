<?php /** @noinspection PhpVoidFunctionResultUsedInspection */

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Files\FilesMatchingRequest;
use App\Models\File;
use Illuminate\Support\Facades\DB;
use function count;

class IndexAllPublishedDatasetsForFilesMatchingApiController extends Controller
{
    public function __invoke(FilesMatchingRequest $request)
    {
        $validated = $request->validated();
        $matches = $validated['match'];
        return File::with(['directory', 'owner'])
                   ->whereIn('dataset_id',
                       DB::table('datasets')
                         ->select('id')
                         ->whereNotNull('published_at'))
                   ->where(function ($query) use ($matches) {
                       for ($i = 0; $i < count($matches); $i++) {
                           $query->orWhere('name', 'like', $matches[$i]);
                       }
                   })
                   ->jsonPaginate();
    }
}
