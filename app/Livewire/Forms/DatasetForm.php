<?php

namespace App\Livewire\Forms;

use App\Models\Dataset;
use Livewire\Form;

class DatasetForm extends Form
{
    public Dataset $dataset;

    public $name;
    public $description;
    public $summary;
    public $tags;

    public function rules()
    {
        return [
            'name'        => 'required|string|max:200',
            'description' => 'nullable|string|max:8192',
            'summary'     => 'nullable|string|max:100',
            //            'license'                     => 'nullable|string|max:256',
            //            'authors'                     => 'nullable|string|max:2048',
            //            'funding'                     => 'nullable|string|max:8192',
            //            'action'                      => 'nullable|string',
            //            'experiments'                 => 'nullable|array',
            //            'communities'                 => 'nullable|array',
            //            'tags'                        => 'nullable|array',
            //            'tags.*.value'                => 'required|string',
            //            'papers'                      => 'nullable|array',
            //            'papers.*.name'               => 'required|string',
            //            'papers.*.reference'          => 'required|string',
            //            'papers.*.doi'                => 'nullable|string',
            //            'papers.*.url'                => 'nullable|url',
            //            'existing_papers'             => 'nullable|array',
            //            'existing_papers.*.name'      => 'required|string',
            //            'existing_papers.*.reference' => 'required|string',
            //            'existing_papers.*.doi'       => 'nullable|string',
            //            'existing_papers.*.url'       => 'nullable|url',
            //            'ds_authors'                  => 'nullable|array',
            //            'ds_authors.*.name'           => 'required|string',
            //            'ds_authors.*.affiliations'   => 'nullable|string',
            //            'ds_authors.*.email'          => 'required|email',
            //            'file1_id'                    => 'nullable|integer',
            //            'file2_id'                    => 'nullable|integer',
            //            'file3_id'                    => 'nullable|integer',
            //            'file4_id'                    => 'nullable|integer',
            //            'file5_id'                    => 'nullable|integer',
        ];
    }

    public function setDataset(Dataset $dataset)
    {
        $this->dataset = $dataset;
        $this->name = $this->dataset->name;
        $this->description = $this->dataset->description;
        $this->summary = $this->dataset->summary;
    }

    public function update()
    {
        $this->validate();

        $this->dataset->name = $this->name;
        $this->dataset->description = $this->description;
        $this->dataset->summary = $this->summary;

        $this->dataset->save();
    }
}
