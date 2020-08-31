<?php

namespace App\Actions\Server;

class GetServerInfoAction
{
    public function execute()
    {
        return [
            'globus_endpoint_id' => config('globus.endpoint'),
            'institution'        => config('server.institution'),
            'version'            => config('server.version'),
            'last_updated_at'    => config('server.last_updated_at'),
            'first_deployed_at'  => config('server.first_deployed_at'),
            'contact'            => config('server.contact'),
            'description'        => config('server.description'),
            'name'               => config('app.name'),
            'uuid'               => config('server.uuid'),
        ];
    }
}