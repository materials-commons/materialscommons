<?php

namespace App\Actions\GoogleSheets;

use App\Models\Project;
use App\Models\Sheet;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use function auth;

class CreateGoogleSheetAction
{
    public function execute($sheetUrl, Project $project, User $user)
    {
        $existing = Sheet::where("url", $sheetUrl)
                         ->where("project_id", $project->id)
                         ->first();
        if (!is_null($existing)) {
            return $existing;
        }

        $getGoogleSheetNameAction = new GetGoogleSheetNameAction();
        $title = $getGoogleSheetNameAction->execute($sheetUrl);

        $sheet = new Sheet([
            'uuid'       => Uuid::uuid4()->toString(),
            'url'        => $sheetUrl,
            'title'      => $title,
            'owner_id'   => auth()->id(),
            'project_id' => $project->id,
        ]);
        $sheet->save();

        return $sheet;
    }
}