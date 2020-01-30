<?php

namespace App\Http\Controllers\Web\Welcome;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutWebController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('about');
    }
}
