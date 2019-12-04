<?php

namespace App\Actions\Published\Datasets\Comments;

use App\Models\Comment;
use App\Models\Dataset;

class CreatePublishedDatasetCommentAction
{
    public function __invoke(Dataset $dataset, $data, $ownerId)
    {
        $comment = Comment::create([
            'title'    => $data['title'],
            'body'     => $data['body'],
            'owner_id' => $ownerId,
        ]);
        $dataset->comments()->save($comment);

        return $dataset;
    }
}