<?php

namespace App\Http\Controllers\Web\Experiments;

use App\Models\Project;

trait ExcelFilesCount
{
    public function getExcelFilesCount(Project $project)
    {
        return $project->files()
                       ->where('mime_type',
                           "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
                       ->where('current', true)
                       ->count();
    }
}