<?php

namespace App\ViewModels\DataDictionary;

use App\Models\Project;
use Spatie\ViewModels\ViewModel;

class ShowDataDictionaryViewModel extends ViewModel
{
    /** @var \App\Models\Project */
    private $project;
    private $entityAttributes;
    private $activityAttributes;

    public function withProject(Project $project)
    {
        $this->project = $project;
        return $this;
    }

    public function withEntityAttributes($entityAttributes)
    {
        $this->entityAttributes = $entityAttributes;
        return $this;
    }

    public function withActivityAttributes($activityAttributes)
    {
        $this->activityAttributes = $activityAttributes;
        return $this;
    }

    public function project()
    {
        return $this->project;
    }

    public function entityAttributes()
    {
        return $this->entityAttributes;
    }

    public function activityAttributes()
    {
        return $this->activityAttributes;
    }

    public function units($c)
    {
        return implode(",", $c->pluck('unit')->unique()->toArray());
    }

    public function min($c)
    {
        return $c->pluck('val')
                 ->map(function ($val) {
                     $decoded = json_decode($val, true);
                     return $decoded["value"];
                 })
                 ->filter(function ($val) {
                     return is_numeric($val);
                 })
                 ->min();
    }

    public function max($c)
    {
        return $c->pluck('val')
                 ->map(function ($val) {
                     $decoded = json_decode($val, true);
                     return $decoded["value"];
                 })
                 ->filter(function ($val) {
                     return is_numeric($val);
                 })
                 ->max();
    }

    public function median($c)
    {
        return $c->pluck('val')
                 ->map(function ($val) {
                     $decoded = json_decode($val, true);
                     return $decoded["value"];
                 })
                 ->filter(function ($val) {
                     return is_numeric($val);
                 })
                 ->median();
    }

    public function mode($c)
    {
        $val = $c->pluck('val')
                 ->map(function ($val) {
                     $decoded = json_decode($val, true);
                     return $decoded["value"];
                 })
                 ->mode();
        return is_null($val) ? $val : $val[0];
    }

    public function numberOfMeasurements($c)
    {
        return $c->count();
    }
}
