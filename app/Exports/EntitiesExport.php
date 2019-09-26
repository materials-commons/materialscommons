<?php

namespace App\Exports;

use App\Models\Entity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EntitiesExport implements FromCollection, ShouldAutoSize, WithHeadings
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

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'id', 'uuid', 'name', 'description', 'owner_id', 'project_id', 'created_at', 'updated_at',
        ];
    }
}
