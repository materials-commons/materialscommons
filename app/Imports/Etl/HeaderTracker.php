<?php

namespace App\Imports\Etl;

class HeaderTracker
{
    public $headersByIndex;

    public function __construct()
    {
        $this->headersByIndex = array();
    }

    public function addHeader(AttributeHeader $header)
    {
        array_push($this->headersByIndex, $header);
    }
}