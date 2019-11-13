<?php

namespace App\Http\Controllers\Web\Projects\Users;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class CreateProjectUsersWebController extends Controller
{
    public function __invoke($projectId)
    {
        $project = Project::with('users')->findOrFail($projectId);
        $users = DB::table('users')->select('*')
                   ->whereNotIn('id', function($q) {
                       $q->select('user_id')->from('project2user')->where('project_id', 1);
                   })->get();
        return view('app.projects.users.create', compact('project', 'users'));
    }
}
