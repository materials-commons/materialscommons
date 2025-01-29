<?php

namespace App\Traits\Table;

use Livewire\Attributes\Url;

trait BaseLivewireTable
{
    #[Url]
    public $search = '';

    #[Url]
    public $sortCol = '';

    #[Url]
    public bool $sortAsc = false;

    public function sortBy($column)
    {
        if ($this->sortCol == $column) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortCol = $column;
            $this->sortAsc = false;
        }
    }
}