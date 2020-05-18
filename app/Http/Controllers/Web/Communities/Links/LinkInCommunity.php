<?php

namespace App\Http\Controllers\Web\Communities\Links;

use App\Models\Community;
use App\Models\Link;
use App\Traits\GetId;
use Illuminate\Support\Facades\DB;

trait LinkInCommunity
{
    use GetId;

    public function linkInCommunity(Community $community, Link $link)
    {
        $communityId = $this->getId($community);
        $linkId = $this->getId($link);

        $count = DB::table('item2link')
                   ->where('item_id', $this->getId($community))
                   ->where('item_type', Community::class)
                   ->where('link_id', $this->getId($link))
                   ->count();
        return $count != 0;
    }
}


