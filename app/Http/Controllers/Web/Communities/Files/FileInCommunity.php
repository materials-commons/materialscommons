<?php

namespace App\Http\Controllers\Web\Communities\Files;

use App\Models\Community;
use App\Models\File;
use Illuminate\Support\Facades\DB;

trait FileInCommunity
{
    public function fileInCommunity(Community $community, File $file)
    {
        $count = DB::table('item2file')
                   ->where('item_id', $community->id)
                   ->where('item_type', Community::class)
                   ->where('file_id', $file->id)
                   ->count();
        return $count != 0;
    }
}
