<?php

namespace App\Http\Controllers\Web\Communities\Files;

use App\Models\Community;
use App\Models\File;
use App\Traits\GetId;
use Illuminate\Support\Facades\DB;

trait FileInCommunity
{
    use GetId;

    public function fileInCommunity(Community $community, File $file)
    {
        $count = DB::table('item2file')
                   ->where('item_id', $this->getId($community))
                   ->where('item_type', Community::class)
                   ->where('file_id', $this->getId($file))
                   ->count();
        return $count != 0;
    }
}
