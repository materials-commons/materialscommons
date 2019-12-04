<?php

namespace App\Http\Controllers\Web\Published\Datasets\Comments;

use App\Http\Controllers\Controller;
use App\Models\Dataset;

class CreateDatasetCommentWebController extends Controller
{
    public function __invoke(Dataset $dataset)
    {
        return view('public.comments.create', compact('dataset'));
    }
}
