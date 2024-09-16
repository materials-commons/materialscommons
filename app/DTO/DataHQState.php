<?php

namespace App\DTO;

class DataHQState
{
    public $whatToGet;
    public $loadedQuery;
    public $dataFor;
    public $filter;
    public $samples;
    public $computations;
    public $processes;

    public function __construct()
    {
        $this->whatToGet = "";
        $this->loadedQuery = "";
        $this->filter = "";
        $this->dataFor = "";
        $this->samples = collect();
        $this->computations = collect();
        $this->processes = collect();
    }
}