<?php

namespace App\Livewire\Projects\Datasets\Crud;

use App\Livewire\Forms\Datasets\AuthorForm;
use Livewire\Component;

class Authors extends Component
{
    public $dataset;
    public AuthorForm $form;

    public function addAuthor()
    {
        $this->dataset = $this->form->updateAuthors($this->dataset);
        $this->form->reset();
    }

    public function move($author, $to)
    {
        $authors = $this->dataset->ds_authors;
        $index = $this->findAuthorIndex($author);
        if ($index == -1) {
            return;
        }

        $out = array_splice($authors, $index, 1);
        array_splice($authors, $to, 0, $out);
        $this->dataset->update(["ds_authors" => $authors]);
    }

    public function remove($author)
    {
        $authors = $this->dataset->ds_authors;
        $index = $this->findAuthorIndex($author);
        if ($index == -1) {
            return;
        }
        unset($authors[$index]);
        $this->dataset->update(["ds_authors" => $authors]);
    }

    private function findAuthorIndex($author): int
    {
        $authors = $this->dataset->ds_authors;
        $index = -1;
        for ($i = 0; $i < count($authors); $i++) {
            if ($authors[$i]["email"] == $author) {
                $index = $i;
                break;
            }
        }
        return $index;
    }

    public function render()
    {
        return view('livewire.projects.datasets.crud.authors');
    }
}
