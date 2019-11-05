<?php

namespace App\Actions\Globus;

class EndpointAclRule
{
    public $principalType;
    public $identityId;
    public $path;
    public $permissions;
    public $endpointId;

    public function __construct($principalType, $identityId, $path, $permissions, $endpointId)
    {
        $this->principalType = $principalType;
        $this->identityId = $identityId;
        $this->path = $path;
        $this->permissions = $permissions;
        $this->endpointId = $endpointId;
    }
}