<?php

namespace App\Http\Controllers\Web\DataHQ\SamplesHQ;

use App\DTO\DataHQ\ChartRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataHQ\ChartDataRequest;
use App\Models\Project;
use App\Traits\Charts\GetChartData;
use App\Traits\DataDictionaryQueries;
use Illuminate\Support\Facades\Storage;
use function response;
use function uniqid;

class DownloadDataForChartWebController extends Controller
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

        $temporaryCSVFilePath = $this->createTemporaryCSVFileOfData($xyMatches, $chartDataRequest);
        return response()->download($temporaryCSVFilePath, "chartdata.csv")->deleteFileAfterSend();
    }

    private function createTemporaryCSVFileOfData(mixed $xyMatches, ChartRequestDTO $chartDataRequest)
    {
        if (count($xyMatches) == 0) {
            return ''; // do something here
        }
        $csv = implode(',', [$chartDataRequest->xattr, $chartDataRequest->yattr, "entity"])."\n";
        foreach ($xyMatches as $xyMatch) {
            $csv .= implode(',', [$xyMatch->x, $xyMatch->y, $xyMatch->entity])."\n";
        }

        $filename = uniqid().'.csv';
        @Storage::disk('mcfs')->makeDirectory('__temp');
        $filePath = Storage::disk('mcfs')->path('__temp/'.$filename);
        file_put_contents($filePath, $csv);

        return $filePath;
    }
}
