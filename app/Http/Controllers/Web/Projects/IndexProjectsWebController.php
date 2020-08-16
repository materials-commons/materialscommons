<?php

namespace App\Http\Controllers\Web\Projects;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Traits\Projects\UserProjects;

class IndexProjectsWebController extends Controller
{
    use UserProjects;
    /**
     * List users projects
     */
    public function __invoke()
    {
        return view('app.projects.index', [
            'projects'          => $this->getUserProjects(auth()->id()),
            'publishedDatasets' => $this->getPublishedDatasets(),
        ]);
    }

    private function getPublishedDatasets()
    {
        return Dataset::withCount('views', 'downloads', 'comments')->where('owner_id', auth()->id())
                      ->whereNotNull('published_at')
                      ->get();
    }
}
