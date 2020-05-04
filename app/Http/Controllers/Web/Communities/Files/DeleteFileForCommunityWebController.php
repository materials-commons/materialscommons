<?php

namespace App\Http\Controllers\Web\Communities\Files;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

class DeleteFileForCommunityWebController extends Controller
{
    public function __invoke(Request $request, Community $community, $fileId)
    {
        $community->files()->toggle($fileId);
    }
}
