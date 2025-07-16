<?php

namespace App\Traits\Entities;

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
}