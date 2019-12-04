<?php

namespace App\Http\Controllers\Web\Published\Datasets\Comments;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Dataset;

class EditDatasetCommentWebController extends Controller
{
    public function __invoke(Dataset $dataset, Comment $comment)
    {
        return view('public.comments.edit', compact('dataset', 'comment'));
    }
}
