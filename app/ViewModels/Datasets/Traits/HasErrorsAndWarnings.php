<?php

namespace App\ViewModels\Datasets\Traits;

trait HasErrorsAndWarnings
{
    public function hasErrorsWarningsAndOrRecommendations()
    {
        if (!$this->dataset->hasSelectedFiles()) {
            return true;
        }

        if (blank($this->dataset->doi)) {
            return true;
        }

        if (blank($this->dataset->description)) {
            return true;
        }

        if (strlen($this->dataset->description) < 50) {
            return true;
        }

        if (blank($this->dataset->summary)) {
            return true;
        }

        if (blank($this->dataset->license)) {
            return true;
        }

        if (blank($this->dataset->authors)) {
            return true;
        }

        if (blank($this->dataset->funding)) {
            return true;
        }

        return false;
    }
}