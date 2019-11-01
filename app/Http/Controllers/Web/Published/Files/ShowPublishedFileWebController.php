<?php

namespace App\Http\Controllers\Web\Published\Files;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\File;
use App\ViewModels\Files\ShowFileViewModel;

class ShowPublishedFileWebController extends Controller
{
    public function __invoke(Dataset $dataset, File $file)
    {
        $viewModel = new ShowFileViewModel($file, null, $dataset);
        return view('app.files.show', $viewModel);
    }
}
