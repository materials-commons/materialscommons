<?php

namespace App\Traits;

trait HasTagsInRequest
{
    public $tags = [];

    public function loadTagsFromData($data)
    {
        if (array_key_exists('tags', $data)) {
            $this->tags = collect($data['tags'])->map(function ($tag) {
                return $tag["value"];
            })->toArray();
        }
    }
}

