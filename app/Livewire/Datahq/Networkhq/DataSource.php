<?php

namespace App\Livewire\Datahq\Networkhq;

use App\DTO\DataHQ\NetworkHQ\NetworkGraphDTO;
use App\Models\File;
use App\Models\Project;
use App\Models\Sheet;
use App\Services\FileServices\FilePathService;
use App\Traits\GoogleSheets;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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
        $path = $this->getSpreadsheetPath();
        $this->subsheets = $this->listExcelSheets($path);
//        [$id, $type] = explode(":", $this->selectedSheet, 2);
//        if ($type === "f") {
//            $f = File::findOrFail($id);
//            $path = app(FilePathService::class)->getMcfsPath($f);
//            $this->subsheets = $this->listExcelSheets($path);
//        } else {
//            $sheet = Sheet::findOrFail($id);
//            $path = $this->downloadGoogleSheet($sheet->url);
//            $this->subsheets = $this->listExcelSheets($path);
//        }
    }

    public function updatedSelectedSubsheet()
    {
        $path = $this->getSpreadsheetPath();
        $this->columns = $this->getSheetColumnNames($path, $this->selectedSubsheet);
//        [$id, $type] = explode(":", $this->selectedSheet, 2);
//        if ($type === "f") {
//            $f = File::findOrFail($id);
//            $path = app(FilePathService::class)->getMcfsPath($f);
//            $this->columns = $this->getSheetColumnNames($path, $this->selectedSubsheet);
//        } else {
//            $sheet = Sheet::findOrFail($id);
//            $path = $this->downloadGoogleSheetToNamedFile($sheet->url, "{$id}.xlsx");
//            $this->columns = $this->getSheetColumnNames($path, $this->selectedSubsheet);
//            ray("columns =", $this->columns);
//        }
    }

    public function loadNetworkData(): void
    {
        ray("loadNetworkData called");
        ray("nodeIdColumn = {$this->nodeIdColumn}");
        $path = $this->getSpreadsheetPath();
        $dto = $this->loadDataIntoNetworkGraphDTO($path, $this->selectedSubsheet);
        $this->dispatch('network-data-loaded', data: $dto);
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
            return collect([]);
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

    private function loadDataIntoNetworkGraphDTO($filePath, $sheet)
    {
        $dto = new NetworkGraphDTO();

        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);

        // $sheet can be a name (string) or index (int, 0-based)
        $ws = is_int($sheet) ? $spreadsheet->getSheet($sheet) : $spreadsheet->getSheetByName($sheet);
        if (!$ws) {
            return $dto;
        }

        if (!blank($this->nodeIdColumn)) {
            $dto->nodeIdValues = $this->getColumnDataFromWorksheet($ws, (int) $this->nodeIdColumn);
        }

        if (!blank($this->nodeXColumn) && !blank($this->nodeYColumn)) {
            $nodeXValues = $this->getColumnDataFromWorksheet($ws, (int) $this->nodeXColumn);
            $nodeYValues = $this->getColumnDataFromWorksheet($ws, (int) $this->nodeYColumn);
            $dto->nodePositions = collect();
            for($i = 0; $i < $nodeXValues->count(); $i++) {
                $entry = [$nodeXValues[$i], $nodeYValues[$i]];
                $dto->nodePositions->push($entry);
            }
        }

        if (!blank($this->edgeStartColumn) && !blank($this->edgeEndColumn)) {
            $edgeStartValues = $this->getColumnDataFromWorksheet($ws, (int) $this->edgeStartColumn);
            $edgeEndValues = $this->getColumnDataFromWorksheet($ws, (int) $this->edgeEndColumn);
            $dto->edges = collect();
            for($i = 0; $i < $edgeStartValues->count(); $i++) {
                $entry = [$edgeStartValues[$i], $edgeEndValues[$i]];
                $dto->edges->push($entry);
            }
        }

        if (!blank($this->nodeSizeColumn)) {
            $dto->nodeSizeAttributeName = $this->getColumnName($this->nodeSizeColumn);
            $dto->nodeSizeAttributeValues = $this->getColumnDataFromWorksheet($ws, (int) $this->nodeSizeColumn);
        }

        if (!blank($this->nodeColorColumn)) {
            $dto->nodeColorAttributeName = $this->getColumnName($this->nodeColorColumn);
            $dto->nodeColorAttributeValues = $this->getColumnDataFromWorksheet($ws, (int) $this->nodeColorColumn);
        }

        if (!blank($this->edgeColorColumn)) {
            $dto->edgeColorAttributeName = $this->getColumnName($this->edgeColorColumn);
            $dto->edgeColorAttributeValues = $this->getColumnDataFromWorksheet($ws, (int) $this->edgeColorColumn);
        }

        return $dto;
    }

    private function getColumnName($colIndex): string {
        foreach($this->columns as $col) {
            if ($col[0] == $colIndex) {
                return $col[1];
            }
        }

        return "";
    }

    private function getColumnDataFromWorksheet(Worksheet $ws, int $columnIndex): Collection
    {
        $highestCol = Coordinate::columnIndexFromString($ws->getHighestDataColumn());
        if ($columnIndex < 1 || $columnIndex > $highestCol) {
            // out of range
            return collect([]);
        }

        $highestRow = $ws->getHighestDataRow();
        $headerRow = 1; // assume header is row 1
        $startRow = $headerRow + 1;

        $columnLetter = Coordinate::stringFromColumnIndex($columnIndex);
        $values = [];

        for ($row = $startRow; $row <= $highestRow; $row++) {
            $value = $ws->getCell($columnLetter.$row)->getCalculatedValue();
            if (!blank($value)) {
                $values[] = $value;
            }
        }

        return collect($values);
    }

    private function getSpreadsheetPath(): string{
        [$id, $type] = explode(":", $this->selectedSheet, 2);
        if ($type === "f") {
            $f = File::findOrFail($id);
            return app(FilePathService::class)->getMcfsPath($f);
        } else {
            $sheet = Sheet::findOrFail($id);
            return $this->downloadGoogleSheetToNamedFile($sheet->url, "{$id}.xlsx");
        }
    }

}
