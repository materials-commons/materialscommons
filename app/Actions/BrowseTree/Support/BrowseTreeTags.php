<?php

namespace App\Actions\BrowseTree\Support;

class BrowseTreeTags
{
    public static function forModel(object $model): array
    {
        if (!method_exists($model, 'relationLoaded') || !$model->relationLoaded('tags')) {
            return [];
        }

        return collect($model->tags ?? [])
            ->pluck('name')
            ->filter()
            ->values()
            ->all();
    }
}
