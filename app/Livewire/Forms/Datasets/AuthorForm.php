<?php

namespace App\Livewire\Forms\Datasets;

use Livewire\Form;

class AuthorForm extends Form
{
    public $name;
    public $email;
    public $affiliation;

    public function rules()
    {
        return [
            'name'        => 'required',
            'email'       => 'required|email',
            'affiliation' => 'required',
        ];
    }

    public function updateAuthors($dataset)
    {
        $this->validate();
        $authors = $dataset->ds_authors;
        $authors[] = [
            'name'         => $this->name,
            'email'        => $this->email,
            'affiliations' => $this->affiliation,
        ];

        $dataset->update(['ds_authors' => $authors]);
        return $dataset;
    }
}
