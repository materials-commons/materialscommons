<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoreUrlWebController extends Controller
{
    public function __invoke(Request $request, Project $project, File $folder)
    {
        $request->validate([
            'url' => 'required|url',
            'name' => 'required|string|max:150',
        ]);

        $file = new File([
            'uuid' => Str::uuid(),
            'name' => $request->input('name'),
            'mime_type' => 'url',
            'media_type_description' => 'URL',
            'url' => $request->input('url'),
            'owner_id' => Auth::id(),
            'project_id' => $project->id,
            'directory_id' => $folder->id,
        ]);

        $file->save();

        return redirect()->route('projects.folders.show', [$project, $folder])
                         ->with('success', 'URL added successfully');
    }
}
