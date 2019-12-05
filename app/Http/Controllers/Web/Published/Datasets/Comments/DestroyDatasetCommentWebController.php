<?php

namespace App\Http\Controllers\Web\Published\Datasets\Comments;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Dataset;

class DestroyDatasetCommentWebController extends Controller
{
    public function __invoke(Dataset $dataset, Comment $comment)
    {
        $comment->delete();
        return redirect(route('public.datasets.comments.index', [$dataset]));
    }
}
