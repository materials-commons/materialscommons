<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use function collect;
use function is_null;

/**
 * @property integer id
 * @property string uuid
 * @property string description
 * @property string queue
 * @property string container
 * @property integer script_file_id
 * @property mixed created_at
 * @property mixed updated_at
 *
 * @mixin Builder
 */
class Script extends Model
{
    use HasFactory;
    use HasUUID;

    protected $guarded = ['id'];

    protected $casts = [
        'script_file_id' => 'integer',
    ];

    public function scriptFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'script_file_id');
    }

    public static function createScriptForFileIfNeeded(File $file)
    {
        $existingScript = Script::where('script_file_id', $file->id)->first();
        if (!is_null($existingScript)) {
            return $existingScript;
        }

        return Script::create([
            'description'    => 'Create script run',
            'queue'          => 'scripts',
            'container'      => 'mc/mcpyimage',
            'script_file_id' => $file->id,
        ]);
    }

    public static function listForProject(Project $project): Collection
    {
        $dir = File::where('path', "/scripts")
                   ->where('project_id', $project->id)
                   ->whereNull('dataset_id')
                   ->whereNull('deleted_at')
                   ->where('current', true)
                   ->first();

        if (is_null($dir)) {
            return collect();
        }

        $scriptFiles = File::with('directory')
                           ->where('directory_id', $dir->id)
                           ->whereNull('dataset_id')
                           ->whereNull('deleted_at')
                           ->where('current', true)
                           ->where('name', 'like', '%.py')
                           ->get();

        if (($scriptFiles->count() == 0)) {
            return collect();
        }

        return $scriptFiles;
    }
}
