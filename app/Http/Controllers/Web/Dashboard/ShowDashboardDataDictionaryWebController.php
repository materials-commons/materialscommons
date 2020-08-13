<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\Dashboard\ShowDashboardDataDictionaryViewModel;
use Illuminate\Http\Request;

class ShowDashboardDataDictionaryWebController extends Controller
{
    use DataDictionaryQueries;

    public function __invoke(Request $request)
    {
        return view('app.dashboard.index', $this->buildViewModel());
    }

    private function buildViewModel()
    {
        $projectIds = $this->getUserProjectIds();
        return (new ShowDashboardDataDictionaryViewModel())
            ->withActivityAttributes($this->getUniqueActivityAttributesForProjects($projectIds))
            ->withEntityAttributes($this->getUniqueEntityAttributesForProjects($projectIds));
    }

    private function getUserProjectIds()
    {
        return auth()->user()->projects()->select('id')->get()->pluck('id')->toArray();
    }
}
