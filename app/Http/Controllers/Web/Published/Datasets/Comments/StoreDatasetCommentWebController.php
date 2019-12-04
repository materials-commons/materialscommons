<?php

namespace App\Http\Controllers\Web\Published\Datasets\Comments;

use App\Actions\Published\Datasets\Comments\CreatePublishedDatasetCommentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Published\Datasets\Comments\CreateDatasetCommentRequest;
use App\Models\Dataset;

class StoreDatasetCommentWebController extends Controller
{
    public function __invoke(CreateDatasetCommentRequest $request, CreatePublishedDatasetCommentAction $createPublishedDatasetCommentAction,
        Dataset $dataset)
    {
        $validated = $request->validated();
        $createPublishedDatasetCommentAction($dataset, $validated, auth()->id());

        return redirect(route('public.datasets.comments.index', [$dataset]));
    }
}
