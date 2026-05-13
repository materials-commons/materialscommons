<?php

namespace App\Models;

use App\Enums\Etl\EtlRunStatus;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin Builder
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $owner_id
 * @property integer $n_activities
 * @property integer $n_activity_attributes
 * @property integer $n_activity_attribute_values
 * @property integer $n_entities
 * @property integer $n_entity_attributes
 * @property integer $n_entity_attribute_values
 * @property integer $n_sheets
 * @property integer $n_sheets_processed
 * @property integer $n_files
 * @property integer $n_files_not_found
 * @property string $files_not_found
 * @property integer $n_columns
 * @property integer $n_columns_skipped
 * @property string $columns_skipped
 */
class EtlRun extends Model
{
    use HasUUID;
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'owner_id' => 'integer',

        'status'                      => EtlRunStatus::class,
        'progress_percent'            => 'integer',
        'started_at'                  => 'datetime',
        'finished_at'                 => 'datetime',
        'cancel_requested_at'         => 'datetime',
        'summary'                     => 'array',

        // activities
        'n_activities'                => 'integer',
        'n_activity_attributes'       => 'integer',
        'n_activity_attribute_values' => 'integer',

        // entities
        'n_entities'                  => 'integer',
        'n_entity_attributes'         => 'integer',
        'n_entity_attribute_values'   => 'integer',

        // sheets
        'n_sheets'                    => 'integer',
        'n_sheets_processed'          => 'integer',

        // files
        'n_files'                     => 'integer',
        'n_files_not_found'           => 'integer',

        // columns
        'n_columns'                   => 'integer',
        'n_columns_skipped'           => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function etlable()
    {
        return $this->morphTo('etlable');
    }

    public function files()
    {
        return $this->morphToMany(File::class, 'item', 'item2file');
    }

    public function attributes()
    {
        return $this->morphToMany(Attribute::class, 'item', 'item2attribute');
    }

    public function activities()
    {
        return $this->morphToMany(Activity::class, 'item', 'item2activity');
    }

    public function entities()
    {
        return $this->morphToMany(Entity::class, 'item', 'item2entity');
    }

    public function steps()
    {
        return $this->hasMany(EtlRunStep::class)->orderBy('sort_order');
    }

    public function processResults()
    {
        return $this->hasMany(EtlRunProcessResult::class)->orderBy('created_at');
    }

    public function validationMessages()
    {
        return $this->hasMany(EtlRunValidationMessage::class)->orderBy('created_at');
    }

    public function logEntries()
    {
        return $this->hasMany(EtlRunLogEntry::class)->orderBy('created_at');
    }

    public function latestLogEntries()
    {
        return $this->hasMany(EtlRunLogEntry::class)->latest();
    }

    public function isActive(): bool
    {
        return $this->status?->isActive() ?? false;
    }

    public function isFinished(): bool
    {
        return $this->status?->isFinished() ?? false;
    }

    public function markRunning(EtlRunStatus $status, string $currentStep, ?string $message = null): void
    {
        $this->update([
            'status'          => $status,
            'current_step'    => $currentStep,
            'current_message' => $message,
            'started_at'      => $this->started_at ?? Carbon::now(),
        ]);
    }

    public function updateProgress(int $progressPercent, ?string $message = null): void
    {
        $progressPercent = max(0, min(100, $progressPercent));

        $data = [
            'progress_percent' => $progressPercent,
        ];

        if (!is_null($message)) {
            $data['current_message'] = $message;
        }

        $this->update($data);
    }

    public function markCompleted(?array $summary = null): void
    {
        $this->update([
            'status'           => 'completed',
            'progress_percent' => 100,
            'current_step'     => 'completed',
            'current_message'  => 'Import completed successfully.',
            'summary'          => $summary,
            'finished_at'      => Carbon::now(),
        ]);
    }

    public function markFailed(string $message): void
    {
        $this->update([
            'status'          => 'failed',
            'current_step'    => 'failed',
            'current_message' => $message,
            'error_message'   => $message,
            'finished_at'     => Carbon::now(),
        ]);
    }

    public function requestCancellation(): void
    {
        $this->update([
            'cancel_requested_at' => Carbon::now(),
        ]);
    }

    public function cancellationRequested(): bool
    {
        return !is_null($this->cancel_requested_at);
    }

    public function logPath(): string
    {
        return Storage::disk('mcfs')->path("etl_logs/{$this->uuid}.log");
    }

    public function deleteLog()
    {
        return Storage::disk('mcfs')->delete("etl_logs/{$this->uuid}.log");
    }

    public static function getOldEtlLogs()
    {
        return EtlRun::where('created_at', '<=', Carbon::now()->subDays(30))->limit(100)->get();
    }
}
