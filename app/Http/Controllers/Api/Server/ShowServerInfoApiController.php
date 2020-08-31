<?php

namespace App\Http\Controllers\Api\Server;

use App\Actions\Server\GetServerInfoAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Server\ServerResource;

class ShowServerInfoApiController extends Controller
{
    public function __invoke(GetServerInfoAction $serverInfoAction)
    {
        return new ServerResource($serverInfoAction->execute());
    }
}
