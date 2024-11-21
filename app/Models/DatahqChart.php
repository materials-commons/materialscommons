<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class DatahqChart extends Model
{
    use HasFactory;
    use HasUUID;
    use HasTags;

    protected $guarded = ['id'];
    protected $casts = [];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function experiment()
    {
        return $this->belongsTo(Experiment::class, 'experiment_id');
    }

    public function dataset()
    {
        return $this->belongsTo(Dataset::class, 'dataset_id');
    }

    public function datahqTabs()
    {
        return $this->belongsToMany(DatahqTab::class, 'datahq_tab2chart', 'chart_id', 'tab_id');
    }
}
