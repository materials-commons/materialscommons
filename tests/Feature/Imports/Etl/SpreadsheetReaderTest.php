<?php

namespace Tests\Feature\Imports\Etl;

use App\Actions\Etl\ChunkReadFilter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Tests\TestCase;

class SpreadsheetReaderTest extends TestCase
{
    /** @test */
    public function read_spreadsheet_cells()
    {
        $reader = new Xlsx();
//        $reader->setReadEmptyCells(false);
        $reader->setReadDataOnly(true);
//        $chunkFilter = new ChunkReadFilter();
//        $reader->setReadFilter($chunkFilter);
        $file = Storage::disk('test_data')->path("etl/d1.xlsx");
        $spreadsheet = $reader->load($file);

        $chunkSize = 1;

        $worksheets = $spreadsheet->getAllSheets();
        foreach($worksheets as $worksheet) {
            $title = $worksheet->getTitle();
            echo "working on worksheet {$title}\n";
            $rowNumber = 0;
            $highest = $worksheet->getHighestRow();
            echo "highest row number = {$highest}\n";
            foreach($worksheet->getRowIterator() as $row) {
                echo "rowNumber = {$rowNumber}\n";
                $cellIterator = $row->getCellIterator();
                try {
                    $cellIterator->setIterateOnlyExistingCells(true);
                } catch (\Exception $e) {
                    break;
                }
                foreach($cellIterator as $cell) {
                    $value = $cell->getFormattedValue();
                    if (is_null($value)) {
                        continue;
                    }
                    if ($value == "") {
                        continue;
                    }
                    echo "Value = {$value} for row ${rowNumber}\n";
                }
                $rowNumber++;
            }
        }
        $this->assertTrue(true);
    }
}
