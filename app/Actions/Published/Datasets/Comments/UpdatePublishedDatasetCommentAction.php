<?php

namespace App\Actions\Published\Datasets\Comments;

use App\Models\Comment;

class UpdatePublishedDatasetCommentAction
{
    public function __invoke(Comment $comment, $data)
    {
        return tap($comment)->update($data)->fresh();
    }
}