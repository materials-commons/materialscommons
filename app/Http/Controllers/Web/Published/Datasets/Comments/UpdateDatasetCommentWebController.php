<?php

namespace App\Http\Controllers\Web\Published\Datasets\Comments;

use App\Actions\Published\Datasets\Comments\UpdatePublishedDatasetCommentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Published\Datasets\Comments\UpdateDatasetCommentRequest;
use App\Models\Comment;
use App\Models\Dataset;

class UpdateDatasetCommentWebController extends Controller
{
    public function __invoke(UpdateDatasetCommentRequest $request, UpdatePublishedDatasetCommentAction $updatePublishedDatasetCommentAction,
        Dataset $dataset, Comment $comment)
    {
        $validated = $request->validated();
        $updatePublishedDatasetCommentAction($comment, $validated);

        return redirect(route('public.datasets.comments.index', [$dataset, $comment]));
    }
}
