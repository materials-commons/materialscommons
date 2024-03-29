<?php

namespace App\Http\Controllers\Web2\Projects\Folders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectFileUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->file->storeAs('files', $request->file->getClientOriginalName());
    }
}
