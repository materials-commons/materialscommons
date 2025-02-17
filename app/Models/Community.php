<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property integer $owner_id
 * @property string $description
 * @property boolean $public
 * @property mixed $datasets
 * @property mixed $publishedDatasets
 *
 * @mixin Builder
 */
class Community extends Model implements Searchable
{
    use HasUUID;
    use HasFactory;

    protected $guarded = ['id', 'uuid'];

    protected $casts = [
        'owner_id' => 'integer',
        'public'   => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function datasets()
    {
        return $this->belongsToMany(Dataset::class, 'dataset2community', 'community_id', 'dataset_id');
    }

    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'attributable');
    }

    public function publishedDatasets()
    {
        return $this->belongsToMany(Dataset::class, 'dataset2community', 'community_id', 'dataset_id')
                    ->whereNotNull('published_at');
    }

    public function datasetsWaitingForApproval()
    {
        return $this->belongsToMany(Dataset::class, 'community2ds_waiting_approval', 'community_id', 'dataset_id');
    }

    public function links()
    {
        return $this->morphToMany(Link::class, 'item', 'item2link');
    }

    public function files()
    {
        return $this->morphToMany(File::class, 'item', 'item2file');
    }

    public function getTypeAttribute()
    {
        return "community";
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('communities.show', [$this]);
        if (Request::routeIs('public.*')) {
            $url = route('public.communities.datasets.index', [$this]);
        }
        return new SearchResult($this, $this->name, $url);
    }

    public function userCanAccess($ownerId)
    {
        if ($this->public) {
            return true;
        }

        return $this->owner_id === $ownerId;
    }
}
