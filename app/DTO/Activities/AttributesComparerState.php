<?php

namespace App\DTO\Activities;

use Illuminate\Support\Collection;

class AttributesComparerState
{
    public Collection $activity1OnlyAttributes ;
    public Collection $activity2OnlyAttributes ;
    public Collection $differentValueAttributes;
    public bool $hideSame;
    public bool $hideDifferent;
    public bool $hideUniqueOnLeft;
    public bool $hideUniqueOnRight;

    public function __construct()
    {
        $this->activity1OnlyAttributes = collect();
        $this->activity2OnlyAttributes = collect();
        $this->differentValueAttributes = collect();
        $this->hideSame = false;
        $this->hideDifferent = false;
        $this->hideUniqueOnLeft = false;
        $this->hideUniqueOnRight = false;
    }
}