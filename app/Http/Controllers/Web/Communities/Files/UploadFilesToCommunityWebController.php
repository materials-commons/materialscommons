<?php

namespace App\Http\Controllers\Web\Communities\Files;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

class UploadFilesToCommunityWebController extends Controller
{
    public function __invoke(Request $request, Community $community)
    {
        return view('app.communities.upload-files', ['community' => $community]);
    }
}
