<?php

namespace App\Imports\Etl2;

use App\Models\Cell;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;

class SpreadsheetCellsImporter implements OnEachRow
{
    private $fileId;

    public function __construct($fileId)
    {
        $this->fileId = $fileId;
    }

    /*
     * public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = $row->toArray();

        $group = Group::firstOrCreate([
            'name' => $row[1],
        ]);

        $group->users()->create([
            'name' => $row[0],
        ]);
    }
     */
    /**
     * @inheritDoc
     */
    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $cells = $row->toArray();

        $columnIndex = 0;
        foreach ($cells as $cell) {
            Cell::create([
                'file_id' => $this->fileId,
                'row'     => $rowIndex,
                'column'  => $columnIndex,
                'value'   => $cell,
            ]);
            $columnIndex++;
        }
    }
}
