<?php

namespace App\Http\Controllers\Web\Published\Datasets;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;

class ShowCitationsForPaperInPublishedDatasetWebController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Dataset $dataset)
    {
        $doi = $request->get('doi');
        $citations = $dataset->getCitations();
        $paper = $this->getPaperCitationByDOI($citations, $doi);
        return view('public.datasets.citations.paper', [
            'dataset' => $dataset,
            'paper'   => $paper->message,
        ]);
    }

    private function getPaperCitationByDOI($citations, $doi)
    {
        foreach ($citations->papers as $paper) {
            if ($paper->message->doi == $doi) {
                return $paper;
            }
        }

        return null;
    }
}
