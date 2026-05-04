<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UpdateMyResearchSummaryDashboardWebController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'research_summary' => ['nullable', 'string', 'max:10000'],
        ]);

        auth()->user()->update([
            'research_summary' => $validated['research_summary'] ?? null,
        ]);

        return redirect()
            ->route('dashboard.my-research.show')
            ->with('success', 'Research summary updated.');
    }
}
