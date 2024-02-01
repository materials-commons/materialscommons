<?php

namespace App\DTO;

class MCFSTransferRequestStatus
{
    public $activityCount;

    public $lastActivityTime;

    public $activityFound;

    public $status;

    public function __construct($requestStatus)
    {
        $this->activityCount = $requestStatus['activity_count'];
        $this->lastActivityTime = $requestStatus['last_activity_time'];
        $this->activityFound = $requestStatus['activity_found'];
        $this->status = $requestStatus['status'];
    }

    public static function fromArray($items)
    {
        $c = collect();
        foreach($items as $item) {
            $requestStatus = new self($item);
            $uuid = $item['transfer_request']['uuid'];
            $c->put($uuid,  $requestStatus);
        }

        return $c;
    }
}