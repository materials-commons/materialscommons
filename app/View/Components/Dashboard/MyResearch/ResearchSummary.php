<?php

namespace App\View\Components\Dashboard\MyResearch;

use App\Models\User;
use Illuminate\View\Component;
use Illuminate\View\View;

class ResearchSummary extends Component
{
    public User $user;

    public bool $hasSummary;

    public string $modalTitle;

    public string $buttonLabel;

    public function __construct()
    {
        $this->user = auth()->user();
        $this->hasSummary = !blank($this->user->research_summary);

        $this->modalTitle = $this->hasSummary
            ? 'Edit Research Summary'
            : 'Create Research Summary';

        $this->buttonLabel = $this->hasSummary
            ? 'Edit'
            : 'Create Research Summary';
    }

    public function render(): View
    {
        return view('components.dashboard.my-research.research-summary');
    }
}
