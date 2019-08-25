<?php

namespace App\Http\Queries\Entities;

use App\Models\Entity;
use App\Traits\GetRequestParameterId;
use Illuminate\Http\Request;

class SingleEntityQuery extends EntitiesQueryBuilder
{
    use GetRequestParameterId;

    public function __construct(?Request $request = null)
    {
        $entityId = $this->getParameterId('entity');
        $query = Entity::where('id', $entityId);
        parent::__construct($query, $request);
    }
}
