<?php

namespace App\Actions\Migration;

use App\Models\Dataset;
use App\Models\View;

class ImportDatasetViews extends AbstractImporter
{
    private $anonymountCounter;

    public function __construct($pathToDumpfiles, $ignoreExisting = false)
    {
        parent::__construct($pathToDumpfiles, "views", $ignoreExisting);
        $this->anonymountCounter = 0;
    }

    protected function setup()
    {
    }

    protected function cleanup()
    {
    }

    protected function getModelClass()
    {
        return View::class;
    }

    protected function shouldLoadRelationshipsOnSkip()
    {
        return false;
    }

    protected function loadRelationships($item)
    {
    }

    protected function loadData($data)
    {
        $dsUuid = $data['dataset_id'];
        $ds = Dataset::findByUuid($dsUuid);
        if ($ds == null) {
            return null;
        }

        $who = $data['user_id'];
        if ($who == 'anonymous') {
            $who = $who."{$this->anonymountCounter}";
            $this->anonymountCounter++;
        }

        return View::create([
            'viewable_id'   => $ds->id,
            'viewable_type' => Dataset::class,
            'who'           => $who,
        ]);
    }
}