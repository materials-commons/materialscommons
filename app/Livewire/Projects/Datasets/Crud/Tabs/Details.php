<?php

namespace App\Livewire\Projects\Datasets\Crud\Tabs;

use App\Livewire\Forms\DatasetForm;
use App\Models\Community;
use App\Models\Dataset;
use Livewire\Component;

class Details extends Component
{
    public DatasetForm $form;

    public $showSuccess = false;

    public $communityId;

    public function mount(Dataset $dataset)
    {
        $this->form->setDataset($dataset);
    }

    public function addCommunity(): void
    {
        $community = Community::find($this->communityId);
        $this->form->dataset->communities()->syncWithoutDetaching($community);
    }

    public function deleteCommunity($communityId): void
    {
        $this->form->dataset->communities()->detach($communityId);
    }

    public function save()
    {
        ray('save');
        $this->form->update();
        $this->showSuccess = true;
    }

    public function done()
    {
        ray('done');
//        $this->save();
        $this->form->update();
        return redirect(route('projects.datasets.index', [$this->form->dataset->project_id]));
    }

    public function setTags($tags)
    {
        if (empty($tags)) {
            return;
        }

        $changed = collect(json_decode($tags))->pluck('value')->toArray();
        $this->form->tags = $changed;
        $this->form->dataset->syncTags($changed);
    }

    public function getTagsAsString()
    {
        return $this->form->dataset->tags->map(fn($tag) => $tag->name)->implode(', ');
    }

    public function render()
    {
        $communities = Community::query()
                                ->whereDoesntHave("datasets", function ($q) {
                                    $q->where('dataset_id', $this->form->dataset->id);
                                })
                                ->orderBy('name')
                                ->get();

        return view('livewire.projects.datasets.crud.tabs.details', [
            'communities' => $communities,
        ]);
    }
}
