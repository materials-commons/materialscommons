<?php

namespace App\Http\Controllers\Web\Trash;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class IndexTrashWebController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('', [
            'projects' => Project::getDeletedForUser(auth()->id()),
        ]);
    }
}
