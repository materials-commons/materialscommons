<?php

namespace App\Livewire\Datahq\Networkhq;

use App\Models\File;
use App\Models\Project;
use App\Models\Sheet;
use App\Services\FileServices\FilePathService;
use App\Traits\GoogleSheets;
use Illuminate\Support\Collection;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DataSource extends Component
{
    use GoogleSheets;

    public Project $project;
    public $selectedSheet = "";
    public $subsheets;
    public $selectedSubsheet = "";

    public function __construct()
    {
        $this->subsheets = collect();
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

//    public function updatedSelectedSubsheet()
//    {
//        ray("updatedSelectedSubsheet called");
//    }

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
}
