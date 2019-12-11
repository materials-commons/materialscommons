<?php

namespace App\Actions\Globus;

class EndpointAclRule
{
    public $principalType;
    public $identityId;
    public $path;
    public $permissions;
    public $endpointId;

    public function __construct($identityId, $path, $permissions, $endpointId, $principalType = "identity")
    {
        $this->principalType = $principalType;
        $this->identityId = $identityId;
        $this->path = $path;
        $this->permissions = $permissions;
        $this->endpointId = $endpointId;
    }
}