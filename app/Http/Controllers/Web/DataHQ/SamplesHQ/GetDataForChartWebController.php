<?php

namespace App\Http\Controllers\Web\DataHQ\SamplesHQ;

use App\DTO\DataHQOld\ChartRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataHQ\ChartDataRequest;
use App\Models\Project;
use App\Traits\Charts\GetChartData;
use App\Traits\DataDictionaryQueries;

class GetDataForChartWebController extends Controller
{
    use DataDictionaryQueries;
    use GetChartData;

    public function __invoke(ChartDataRequest $request, Project $project)
    {
        $chartDataRequest = ChartRequestDTO::fromArray($request->validated());

        $xattrValues = $this->getAttributeDataForProject($chartDataRequest->xattrType, $chartDataRequest->xattr,
                                                         $project);
        $yattrValues = $this->getAttributeDataForProject($chartDataRequest->yattrType, $chartDataRequest->yattr,
                                                         $project);

        $xyMatches = $this->createXYMatches($xattrValues->get($chartDataRequest->xattr),
                                            $yattrValues->get($chartDataRequest->yattr));

        return response()->json(array_values($xyMatches));
    }

}
