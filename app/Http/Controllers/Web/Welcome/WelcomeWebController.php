<?php

namespace App\Http\Controllers\Web\Welcome;

use App\Http\Controllers\Controller;
use App\Traits\Projects\UserProjects;
use Illuminate\Http\Request;
use function view;

class WelcomeWebController extends Controller
{
    use UserProjects;
    public function __invoke(Request $request)
    {
        if ($request->get('deviceType') == 'phone') {
            $projects = collect();
            if (auth()->check()) {
                $projects = $this->getUserProjects(auth()->id());
            }
            return view('welcome-phone', [
                'projects' => $projects,
            ]);
        }
        return view('welcome');
    }
}
