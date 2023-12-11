<?php

namespace App\Http\Controllers\Web\Published\UHCSDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowUHCSDBWebController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('public.reference.uhcsdb.show');
    }
}
