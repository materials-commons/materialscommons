<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property integer $id
 * @property string $name
 * @property integer $owner_id
 * @property array $home_page_files
 * @property array $home_page_sections
 * @property \Illuminate\Support\Collection $members
 * @property \Illuminate\Support\Collection $admins
 */
class Team extends Model implements Searchable
{
    use HasUUID;
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'id'                 => 'integer',
        'name'               => 'string',
        'owner_id'           => 'integer',
        'home_page_files'    => 'array',
        'home_page_sections' => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function projects()
    {
        return $this->morphedByMany(Project::class, 'item', 'item2team');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'team2member');
    }

    public function admins()
    {
        return $this->belongsToMany(User::class, 'team2admin');
    }

    public function getTypeAttribute()
    {
        return "team";
    }

    public function getSearchResult(): SearchResult
    {
        $url = "";
        return new SearchResult($this, $this->name, $url);
    }
}
