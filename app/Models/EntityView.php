<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string entity_name
 * @property string activity_name
 * @property string attribute_name
 * @property array attribute_value
 * @property string attribute_unit
 * @property integer project_id
 * @property integer experiment_id
 *
 * @mixin Builder
 */
class EntityView extends Model
{
    protected $table = "entity_attrs_by_proj_exp";
    protected $casts = [
        'project_id'      => 'integer',
        'experiment_id'   => 'integer',
        'attribute_value' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function experiment(): BelongsTo
    {
        return $this->belongsTo(Experiment::class);
    }
}
