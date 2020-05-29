<?php

namespace App\Actions\Links;

use App\Models\Link;

class CreateLinkAction
{
    public function execute($attributes, $ownerId)
    {
        $attributes['owner_id'] = $ownerId;
        return Link::create($attributes);
    }
}