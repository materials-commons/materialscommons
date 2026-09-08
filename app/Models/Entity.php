<?php

namespace App\Models;

use App\DTO\Attributes\FlattenedAttribute;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Tags\HasTags;

/**
 * @property integer $id
 * @property string $uuid
 * @property string name
 * @property integer owner_id
 * @property mixed owner
 * @property string description
 * @property integer $project_id
 * @property mixed experiments
 * @property mixed entityStates
 *
 * @mixin Builder
 */
class Entity extends Model
{
    use HasUUID;
    use HasFactory;
    use HasTags;
    use Searchable;

    protected $guarded = ['id'];

    protected $casts = [
        'owner_id'   => 'integer',
        'project_id' => 'integer',
        'copied_at'  => 'datetime',
    ];

    // Caches for flattening the attributes on an entity. This is used to
    // speed up the search for attributes and to eliminate the need to go
    // through the entity states to get the attributes.
    private $cacheFlattenedAttributes = null;
    private $cacheFlattenedAttributesAsKeyValue = null;


    public static function activityNamesForEntities($entities)
    {
        $entityIds = $entities->pluck('id')->all();
        return DB::table('activity2entity')
                 ->whereIn('entity_id', $entityIds)
                 ->join('activities', 'activity2entity.activity_id', '=', 'activities.id')
                 ->where('activities.name', '<>', 'Create Samples')
                 ->select('activities.name')
                 ->distinct()
                 ->orderBy('activities.name')
                 ->get();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function attributes()
    {
        return $this->morphMany(Attribute::class, 'attributable');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function entityStates()
    {
        return $this->hasMany(EntityState::class);
    }

    public function datasets()
    {
        return $this->belongsToMany(Dataset::class, 'dataset2entity', 'entity_id', 'dataset_id');
    }

    public function experiments()
    {
        return $this->belongsToMany(Experiment::class, 'experiment2entity', 'entity_id', 'experiment_id');
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'entity2file', 'entity_id', 'file_id');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity2entity', 'entity_id', 'activity_id');
    }

    public function workflows()
    {
        return $this->morphToMany(Workflow::class, 'item', 'item2workflow');
    }

    public function etlruns()
    {
        return $this->morphedByMany(EtlRun::class, 'item', 'item2entity');
    }

    public function scopeWhereAttributeValue($query, $attributeName, $operator, $value)
    {
        return $query->whereHas('entityStates.attrs', function ($q) use ($attributeName, $operator, $value) {
            $q->where('name', $attributeName)
              ->whereHas('values', function ($valueQuery) use ($operator, $value) {
                  $valueQuery->where('val', $operator, $value);
              });
        });
    }


    public function getTypeAttribute()
    {
        if ($this->category === 'experimental') {
            return "sample";
        }
        return "computation";
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Customize the data array to include only the fields you want to search
        return [
            'id'          => $array['id'],
            'name'        => $array['name'],
            'description' => $array['description'] ?? '',
            'project_id'  => $array['project_id'],
            'dataset_id'  => $array['dataset_id'] ?? null,
            'summary'     => $array['description'] ?? '',
            'category'    => $array['category'],
            'type'        => $array['category'] === 'experimental' ? 'sample' : 'computation',
        ];
    }

    /**
     * Get the URL for the search result.
     *
     * @return string
     */
    public function getScoutUrl()
    {
        if (is_null($this->dataset_id)) {
            return route('projects.entities.show', [$this->project_id, $this]);
        } else {
            return route('public.datasets.entities.show-spread', [$this->dataset_id, $this]);
        }
    }

    public function getSearchResult(): SearchResult
    {
        if (is_null($this->dataset_id)) {
            $url = route('projects.entities.show', [$this->project_id, $this]);
        } else {
            $url = route('public.datasets.entities.show-spread', [$this->dataset_id, $this]);
        }
        return new SearchResult($this, $this->name, $url);
    }

    public function getFlattenedAttributesAttribute()
    {
        // Return cached results if already computed
        if ($this->cacheFlattenedAttributes !== null) {
            return $this->cacheFlattenedAttributes;
        }

//        if (!$this->relationLoaded('entityStates')) {
            $this->load('entityStates.attrs.values');
//        }

        $flattenedAttributes = collect();

        foreach ($this->entityStates as $entityState) {
            foreach ($entityState->attrs as $attribute) {
                $flattenedAttributes->push(new FlattenedAttribute(
                        id: $attribute->id,
                        name: $attribute->name,
                        values: $attribute->values->pluck('val')->toArray(),
                        entityStateId: $entityState->id,
                    )
                );
            }
        }

        // Cache the result
        $this->cacheFlattenedAttributes = $flattenedAttributes;

        return $this->cacheFlattenedAttributes;
    }

    // Helper method to get attributes as key-value pairs
    public function getFlattenedAttributesAsKeyValueAttribute()
    {
        // Return cached results if already computed
        if ($this->flattenedAttributesAsKeyValueCache !== null) {
            return $this->flattenedAttributesAsKeyValueCache;
        }

        $keyValue = [];

        foreach ($this->flattened_attributes as $attribute) {
            $keyValue[$attribute->name] = $attribute->getValue();
        }

        // Cache the result
        $this->cacheFlattenedAttributesAsKeyValue = $keyValue;

        return $this->cacheFlattenedAttributesAsKeyValue;
    }

// Method to clear the cache if needed (e.g., when relationships are refreshed)
    public function clearFlattenedAttributesCache()
    {
        $this->cacheFlattenedAttributes = null;
        $this->cacheFlattenedAttributesAsKeyValue = null;
        return $this;
    }

// Override the refresh method to clear cache when model is refreshed
    public function refresh()
    {
        $this->clearFlattenedAttributesCache();
        return parent::refresh();
    }


}
