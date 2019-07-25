<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectFileUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->file->storeAs('files', $request->file->getClientOriginalName());
    }
}
