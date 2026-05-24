<?php

namespace App\DTO;

class MCDesktopClientConnection
{
    public $clientId;
    public $hostname;
    public $projectIds;
    public $clientType;
    public $userId;

    public function __construct($request)
    {
        $this->clientId = $request['client_id'];
        $this->hostname = $request['hostname'];
        $this->projectIds = collect($request['projects']);
        $this->clientType = $request['type'];
        $this->userId = $request['user_id'];
    }

    public static function fromArray($items)
    {
        $c = collect();
        if(empty($items)) {
            return $c;
        }
        foreach($items as $item) {
            $clientConnection = new self($item);
            $c->push($clientConnection);
        }

        return $c;
    }
}
