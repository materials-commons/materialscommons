<?php

namespace App\Http\Controllers\Web\Files\Trashcan;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DeleteDirectoryFromTrashcanWebController extends Controller
{
    public function __invoke(Request $request, Project $project, File $dir)
    {
        $dir->update(['deleted_at' => Carbon::now()->subDays(config('trash.expires_in_days') + 1)]);
        return redirect(route('projects.trashcan.index', [$project]));
    }
}
