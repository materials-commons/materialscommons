<?php

namespace App\Models;

use App\Traits\HasUUID;
use App\Traits\Mql\MqlQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SavedQuery
 * @package App\Models
 *
 * @property integer $id
 * @property string name
 * @property string uuid
 * @property string description
 * @property integer owner_id
 * @property integer project_id
 * @property array query
 *
 * @mixin Builder
 */
class SavedQuery extends Model
{
    use HasUUID;
    use HasFactory;
    use MqlQueryBuilder;

    protected $guarded = ['id'];

    protected $casts = [
        'query'      => 'array',
        'owner_id'   => 'integer',
        'project_id' => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function queryText()
    {
        return $this->buildMqlQueryText($this->query);
    }
}
