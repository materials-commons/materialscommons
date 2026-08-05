<?php

namespace App\Livewire\Search;

use App\Services\Search\PlaceholderSearchService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;

class PublicSearchResults extends Component
{
    #[Url(as: 'q')]
    public string $query = '';

    #[Url]
    public string $type = 'all';

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

        return app(PlaceholderSearchService::class)
            ->searchPublished($this->query, $this->type, 10);
    }

    public function render()
    {
        return view('livewire.search.public-search-results', [
            'results' => $this->results,
        ]);
    }
}
