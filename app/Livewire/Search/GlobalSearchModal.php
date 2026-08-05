<?php

namespace App\Livewire\Search;

use App\Models\Project;
use App\Services\Search\PlaceholderSearchService;
use Illuminate\Support\Collection;
use Livewire\Component;

class GlobalSearchModal extends Component
{
    public string $query = '';

    public string $scope = 'published';

    public string $type = 'all';

    public ?int $projectId = null;

    public ?string $projectName = null;

    public function mount(?Project $project = null): void
    {
        if (! is_null($project)) {
            $this->projectId = $project->id;
            $this->projectName = $project->name;

            if (auth()->check()) {
                $this->scope = 'project';
            }
        } elseif (auth()->check()) {
            if ($this->isPublishedDataContext()) {
                $this->scope = 'published';
            } else {
                $this->scope = 'all-projects';
            }
        }
    }

    private function isPublishedDataContext(): bool
    {
        return request()->routeIs(
            'public.*',
            'public.index',
            'search.public',
            'datasets.show-by-doi'
        );
    }

    public function updatedScope(): void
    {
        if ($this->scope === 'project' && is_null($this->projectId)) {
            $this->scope = auth()->check() ? 'all-projects' : 'published';
        }
    }

    public function submit()
    {
        if (blank($this->query)) {
            return null;
        }

        return $this->redirect($this->searchUrl(), navigate: true);
    }

    public function getPreviewResultsProperty(): Collection
    {
        if (mb_strlen(trim($this->query)) < 2) {
            return collect();
        }

        $service = app(PlaceholderSearchService::class);

        return match ($this->scope) {
            'project' => $this->projectId
                ? $service->searchProject(Project::findOrFail($this->projectId), $this->query, $this->type, 3)
                : collect(),
            'all-projects' => auth()->check()
                ? $service->searchAllProjects($this->query, $this->type, 3)
                : collect(),
            default => $service->searchPublished($this->query, $this->type, 3),
        };
    }

    public function searchUrl(): string
    {
        $params = [
            'q' => $this->query,
            'type' => $this->type,
        ];

        if ($this->scope === 'project' && $this->projectId) {
            return route('projects.search', ['project' => $this->projectId] + $params);
        }

        if ($this->scope === 'all-projects' && auth()->check()) {
            return route('projects.search_all', $params);
        }

        return route('search.public', $params);
    }

    public function render()
    {
        return view('livewire.search.global-search-modal', [
            'previewResults' => $this->previewResults,
        ]);
    }
}
