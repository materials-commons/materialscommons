<?php

namespace App\Imports\Etl;

class HashedActivityTracker
{
    private $activitiesByAttrHash;

    public function __construct()
    {
        $this->activitiesByAttrHash = collect();
    }

    public function addActivity($hash, $activity)
    {
        if (!$this->activitiesByAttrHash->has($hash)) {
            $this->activitiesByAttrHash->put($hash, $activity);
        }
    }

    public function getActivity($hash)
    {
        return $this->activitiesByAttrHash->get($hash);
    }
}