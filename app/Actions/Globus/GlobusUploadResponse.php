<?php

namespace App\Actions\Globus;

class GlobusUploadResponse
{
    public $globusUrl;
    public $globusEndpointId;
    public $globusEndpointPath;

    public function __construct($globusUrl, $globusEndpointId, $globusEndpointPath)
    {
        $this->globusUrl = $globusUrl;
        $this->globusEndpointId = $globusEndpointId;
        $this->globusEndpointPath = $globusEndpointPath;
    }
}