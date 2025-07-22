<?php

namespace App\Traits\Entities;

use Illuminate\Support\Str;

trait PrevNextEntity
{
    public $prevEntity;
    public $nextEntity;

    private function computePrevNext($allEntities, $entityId)
    {
        // Find the current entity's position in the list
        $currentIndex = $allEntities->search(function ($item) use ($entityId) {
            return $item->id == $entityId;
        });

        // Determine next and previous entities
        $this->nextEntity = null;
        $this->prevEntity = null;

        if ($currentIndex !== false) {
            if ($currentIndex < $allEntities->count() - 1) {
                $this->nextEntity = $allEntities[$currentIndex + 1];
            }

            if ($currentIndex > 0) {
                $this->prevEntity = $allEntities[$currentIndex - 1];
            }
        }
    }

    private function isFromExperiment($request)
    {
        $routeName = $request->route()->getName();
        if (Str::contains($routeName,'experiments')) {
            return true;
        }

        return $request->input("fromExperiment") == "true";
    }
}