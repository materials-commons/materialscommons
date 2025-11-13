<?php

namespace App\Livewire\Datahq\Networkhq;

use App\Models\File;
use App\Models\Project;
use App\Models\Sheet;
use App\Services\FileServices\FilePathService;
use App\Traits\GoogleSheets;
use Illuminate\Support\Collection;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DataSource extends Component
{
    use GoogleSheets;

    public Project $project;
    public $selectedSheet = "";
    public $subsheets;
    public $selectedSubsheet = "";
    public $columns;

    public $nodeIdColumn;
    public $nodeXColumn;
    public $nodeYColumn;
    public $edgeStartColumn;
    public $edgeEndColumn;
    public $nodeSizeColumn;
    public $nodeColorColumn;
    public $edgeColorColumn;

    public function __construct()
    {
        $this->subsheets = collect();
        $this->columns = collect();
    }

    public function updatedSelectedSheet()
    {
        // Only work with a file first
        [$id, $type] = explode(":", $this->selectedSheet, 2);
        if ($type === "f") {
            $f = File::findOrFail($id);
            $path = app(FilePathService::class)->getMcfsPath($f);
            $this->subsheets = $this->listExcelSheets($path);
        } else {
            $sheet = Sheet::findOrFail($id);
            $path = $this->downloadGoogleSheet($sheet->url);
            $this->subsheets = $this->listExcelSheets($path);
        }
    }

    public function updatedSelectedSubsheet()
    {
        [$id, $type] = explode(":", $this->selectedSheet, 2);
        if ($type === "f") {
            $f = File::findOrFail($id);
            $path = app(FilePathService::class)->getMcfsPath($f);
            $this->columns = $this->getSheetColumnNames($path, $this->selectedSubsheet);
        } else {
            $sheet = Sheet::findOrFail($id);
            $path = $this->downloadGoogleSheet($sheet->url);
            $this->columns = $this->getSheetColumnNames($path, $this->selectedSubsheet);
        }
    }

    public function render()
    {
        ray("DataSource render called");
        $excelFiles = File::where('project_id', $this->project->id)
                          ->whereLike('name', '%.xlsx')
                          ->get();
        $googleSheets = Sheet::where('project_id', $this->project->id)->get();
        $merged = $googleSheets->merge($excelFiles)->sortBy(function ($item) {
            return mb_strtolower($item->title ?? $item->name ?? '');
        }, SORT_NATURAL | SORT_FLAG_CASE)
                               ->values();
        return view('livewire.datahq.networkhq.data-source', [
            'sheets' => $merged,
        ]);
    }

    private function listExcelSheets($path): Collection
    {
        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($path);

        $sheetTitles = [];
        foreach ($spreadsheet->getSheetNames() as $name) {
            $sheetTitles[] = $name;
        }
        return collect($sheetTitles);
    }

    private function getSheetColumnNames(string $filePath, string|int $sheet): Collection
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);

        // $sheet can be a name (string) or index (int, 0-based)
        $ws = is_int($sheet) ? $spreadsheet->getSheet($sheet) : $spreadsheet->getSheetByName($sheet);
        if (!$ws) {
            return [];
        }

        $highestCol = $ws->getHighestDataColumn();        // e.g. 'F'
        $highestColIndex = Coordinate::columnIndexFromString($highestCol); // e.g. 6
        $headerRow = 1; // change if headers are on another row

        $headers = [];
        for ($col = 1; $col <= $highestColIndex; $col++) {
            $header = (string) $ws->getCell(Coordinate::stringFromColumnIndex($col).$headerRow)->getCalculatedValue();
            if (blank($header)) {
                continue;
            }
            $headers[] = [$col, $header];
        }
        return collect($headers);
    }
}
