<?php

namespace App\Http\Controllers\Web\Projects\Users;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ModifyProjectUsersWebController extends Controller
{
    public function __invoke($projectId)
    {
        $project = Project::with('users')->findOrFail($projectId);
        $this->authorize('updateUsers', $project);
        $users = DB::table('users')->select('*')
                   ->whereNotIn('id', function($q) use ($project) {
                       $q->select('user_id')->from('project2user')->where('project_id', $project->id);
                   })->get();
        return view('app.projects.users.edit', compact('project', 'users'));
    }
}
