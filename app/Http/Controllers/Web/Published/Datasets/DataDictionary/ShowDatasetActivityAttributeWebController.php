<?php

namespace App\Http\Controllers\Web\Published\Datasets\DataDictionary;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Traits\DataDictionaryQueries;
use App\ViewModels\Attributes\ShowAttributeViewModel;
use Illuminate\Http\Request;
use function abort_if;
use function is_null;
use function view;

class ShowDatasetActivityAttributeWebController extends Controller
{
    use DataDictionaryQueries;

    public function __invoke(Request $request, Dataset $dataset)
    {
        $attributeName = $request->input('attribute', null);
        abort_if(is_null($attributeName), 400, "Attribute name is required");
        $viewModel = (new ShowAttributeViewModel())
            ->withAttributeName($attributeName)
            ->withDataset($dataset)
            ->withActivityRouteName('public.datasets.activity-attributes.show')
            ->withAttributeValues($this->getActivityAttributeForDataset($dataset->id, $attributeName));
        return view('public.datasets.attributes.show', $viewModel);
    }
}