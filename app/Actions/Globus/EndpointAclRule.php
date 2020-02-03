<?php

namespace App\Actions\Globus;

class EndpointAclRule
{
    public const ACLPrincipalTypeIdentity = "identity";
    public const ACLPrincipalTypeAllAuthenticatedUsers = "all_authenticated_users";

    public $principalType;
    public $identityId;
    public $path;
    public $permissions;
    public $endpointId;

    public function __construct($identityId, $path, $permissions, $endpointId,
        $principalType = EndpointAclRule::ACLPrincipalTypeIdentity)
    {
        $this->principalType = $principalType;
        $this->identityId = $identityId;
        $this->path = $path;
        $this->permissions = $permissions;
        $this->endpointId = $endpointId;
    }
}