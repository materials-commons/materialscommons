<?php

namespace App\Http\Controllers\Web\Published\Datasets\Entities;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Entity;
use Illuminate\Http\Request;

class ShowPublishedDatasetEntityWebController extends Controller
{
    public function __invoke(Request $request, Dataset $dataset, Entity $entity)
    {
        return view('', compact('entity'));
    }
}
