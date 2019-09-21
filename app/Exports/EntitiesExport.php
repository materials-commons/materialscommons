<?php

namespace App\Exports;

use App\Models\Entity;
use Maatwebsite\Excel\Concerns\FromCollection;

class EntitiesExport implements FromCollection
{
    private $projectId;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Entity::where('project_id', $this->projectId)->get();
    }
}
