<?php /** @noinspection PhpVoidFunctionResultUsedInspection */

namespace App\Http\Controllers\Api\Datasets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Files\FilesMatchingRequest;
use App\Models\Dataset;
use App\Models\File;
use function count;

class IndexPublishedDatasetForFilesMatchingApiController extends Controller
{
    public function __invoke(FilesMatchingRequest $request, Dataset $dataset)
    {
        $validated = $request->validated();
        $matches = $validated['match'];
        return File::with(['directory', 'owner'])
                   ->where('dataset_id', $dataset->id)
                   ->whereNull('deleted_at')
                   ->where(function ($query) use ($matches) {
                       for ($i = 0; $i < count($matches); $i++) {
                           $query->orWhere('name', 'like', $matches[$i]);
                       }
                   })
                   ->jsonPaginate();
    }
}
