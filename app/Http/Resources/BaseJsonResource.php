<?php


namespace App\Http\Resources;


class BaseJsonResource
{
    protected $fields = [];

    public function __construct($resource)
    {
        parent::__construct($resource);
    }
}
