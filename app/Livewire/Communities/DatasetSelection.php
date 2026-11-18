<?php

namespace App\Livewire\Communities;

use App\Actions\Communities\UpdateCommunityDatasetSelectionAction;
use App\Models\Community;
use App\Models\Dataset;
use Illuminate\Support\Collection;
use Livewire\Component;

class DatasetSelection extends Component
{
    public Community $community;
    public Collection $datasets;

    public function mount(Community $community, Collection $datasets): void
    {
        abort_unless($community->owner_id === auth()->id(), 404, 'No such community');

        $this->community = $community;
        $this->datasets = $datasets;
    }

    public function toggleDataset(int $datasetId): void
    {
        Dataset::findOrFail($datasetId);

        abort_unless($this->community->owner_id === auth()->id(), 404, 'No such community');

        $action = new UpdateCommunityDatasetSelectionAction();

        $action->execute($this->community, $datasetId);

        $this->community = $this->community->fresh('datasets');
    }

    public function render()
    {
        return view('livewire.communities.dataset-selection');
    }
}
