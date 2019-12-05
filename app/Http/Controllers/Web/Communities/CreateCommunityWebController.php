<?php

namespace App\Http\Controllers\Web\Communities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateCommunityWebController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('app.communities.create');
    }
}
