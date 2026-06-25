<?php

namespace App\Http\Controllers\Web\Welcome;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use App\Models\User;
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
        $publishedDatasetsCount = Dataset::whereNotNull('published_at')
                                         ->whereDoesntHave('tags', function ($q) {
                                             $q->where('tags.id', config('visus.import_tag_id'));
                                         })->count();

        $view = 'welcome3';
//        if (isInBeta('new-front-page')) {
//            $view = 'welcome2';
//        }

        return view($view, [
            'publishedDatasetsCount' => $publishedDatasetsCount,
            'projectsCount'          => Project::count(),
            'usersCount'             => User::count(),
        ]);
    }
}
