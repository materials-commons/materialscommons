<?php

namespace App\Livewire\Search;

use App\Models\Project;
use App\Services\Search\MCSearchService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;

class ProjectSearchResults extends Component
{
    public Project $project;

    #[Url(as: 'q')]
    public string $query = '';

    #[Url]
    public string $type = 'all';

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    public function search(): void
    {
        $this->query = trim($this->query);
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getResultsProperty(): Collection
    {
        if (blank($this->query)) {
            return collect();
        }

        return app(MCSearchService::class)
            ->searchProject($this->project, $this->query, $this->type, 10);
    }

    public function render()
    {
        return view('livewire.search.project-search-results', [
            'results' => $this->results,
        ]);
    }
}
