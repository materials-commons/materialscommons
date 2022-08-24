<?php

namespace App\Imports\Etl;

class HeaderTracker
{
    public $headersByIndex;
    public $headersByIndexLength;

    public function __construct()
    {
        $this->headersByIndex = array();
        $this->headersByIndexLength = sizeof($this->headersByIndex);
    }

    public function addHeader(AttributeHeader $header)
    {
        array_push($this->headersByIndex, $header);
        $this->headersByIndexLength = sizeof($this->headersByIndex);
    }

    public function getHeaderByIndex($index)
    {
        if ($index < 0) {
            return null;
        }

        if ($index >= $this->headersByIndexLength) {
            return null;
        }
        return $this->headersByIndex[$index];
    }
}