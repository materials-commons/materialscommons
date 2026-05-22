<?php

namespace App\Imports\Etl;

use App\Enums\Etl\EtlRunLogLevel;
use App\Enums\Etl\EtlRunProcessResultStatus;
use App\Enums\Etl\EtlRunStatus;
use App\Enums\Etl\EtlRunStepStatus;
use App\Enums\Etl\EtlRunValidationSeverity;
use App\Models\Entity;
use App\Models\EtlRun;
use App\Models\EtlRunLogEntry;
use App\Models\EtlRunProcessResult;
use App\Models\EtlRunProcessResultEntity;
use App\Models\EtlRunStep;
use App\Models\EtlRunValidationMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EtlState
{
    /** @var \App\Models\EtlRun */
    public $etlRun;

    public function __construct($ownerId = null, $fileId = null, ?EtlRun $etlRun = null)
    {
        // If we are being passed an existing etlRun then use it.
        if (!is_null($etlRun)) {
            $this->etlRun = $etlRun;
            $this->initializeSteps();
            return;
        }

        // Create a new etlRun
        $this->etlRun = EtlRun::make([
            'owner_id'                    => $ownerId,
            'status'                      => EtlRunStatus::Queued,
            'progress_percent'            => 0,
            'current_step'                => 'queued',
            'current_message'             => 'Import has been queued.',

            // activities
            'n_activities'                => 0,
            'n_activity_attributes'       => 0,
            'n_activity_attribute_values' => 0,

            // entities
            'n_entities'                  => 0,
            'n_entity_attributes'         => 0,
            'n_entity_attribute_values'   => 0,

            // sheets
            'n_sheets'                    => 0,
            'n_sheets_processed'          => 0,

            // files
            'n_files'                     => 0,
            'n_files_not_found'           => 0,

            // columns
            'n_columns'                   => 0,
            'n_columns_skipped'           => 0,
        ]);

        $this->etlRun->save();

        if (!is_null($fileId)) {
            $this->etlRun->files()->attach($fileId);
        }

        $this->initializeSteps();
        $this->logInfo('Import queued.');
    }

    // Retrieve an existing etlRun by id and initialize EtlState with it
    public static function fromRunId(int $etlRunId): self
    {
        return new self(etlRun: EtlRun::find($etlRunId));
    }

    // Initialize EtlState with an existing EtlRun
    public static function fromRun(EtlRun $etlRun): self
    {
        return new self(etlRun: $etlRun);
    }

    public function initializeSteps(): void
    {
        $steps = [
            [
                'key'        => 'queued',
                'label'      => 'Queued',
                'sort_order' => 10,
            ],
            [
                'key'        => 'read_spreadsheet',
                'label'      => 'Read Spreadsheet',
                'sort_order' => 20,
            ],
            [
                'key'        => 'parse_worksheets',
                'label'      => 'Parse Worksheets',
                'sort_order' => 30,
            ],
            [
                'key'        => 'validate',
                'label'      => 'Validate',
                'sort_order' => 40,
            ],
            [
                'key'        => 'process_worksheets',
                'label'      => 'Process Worksheets',
                'sort_order' => 50,
            ],
            [
                'key'        => 'finalize',
                'label'      => 'Finalize',
                'sort_order' => 60,
            ],
        ];

        foreach ($steps as $step) {
            EtlRunStep::query()->updateOrCreate(
                [
                    'etl_run_id' => $this->etlRun->id,
                    'key'        => $step['key'],
                ],
                [
                    'label'      => $step['label'],
                    'status'     => EtlRunStepStatus::Waiting,
                    'sort_order' => $step['sort_order'],
                ],
            );
        }
    }

    public function startStep(string $key, ?string $message = null): EtlRunStep
    {
        $step = $this->getStep($key);

        $step->update([
            'status'      => EtlRunStepStatus::Running,
            'message'     => $message,
            'started_at'  => $step->started_at ?? Carbon::now(),
            'finished_at' => null,
        ]);

        $this->etlRun->update([
            'status'          => $this->statusForStep($key),
            'current_step'    => $key,
            'current_message' => $message,
            'started_at'      => $this->etlRun->started_at ?? Carbon::now(),
        ]);

        if (!blank($message)) {
            $this->logInfo($message);
        }

        return $step;
    }

    public function completeStep(string $key, ?string $message = null): EtlRunStep
    {
        $step = $this->getStep($key);

        $step->update([
            'status'      => EtlRunStepStatus::Completed,
            'message'     => $message ?? $step->message,
            'finished_at' => Carbon::now(),
        ]);

        if (!blank($message)) {
            $this->logSuccess($message);
        }

        return $step;
    }

    public function completeStepWithWarnings(string $key, ?string $message = null): EtlRunStep
    {
        $step = $this->getStep($key);

        $step->update([
            'status'      => EtlRunStepStatus::CompletedWithWarnings,
            'message'     => $message ?? $step->message,
            'finished_at' => Carbon::now(),
        ]);

        if (!blank($message)) {
            $this->logWarning($message);
        }

        return $step;
    }

    public function failStep(string $key, string $message): EtlRunStep
    {
        $step = $this->getStep($key);

        $step->update([
            'status'      => EtlRunStepStatus::Failed,
            'message'     => $message,
            'finished_at' => Carbon::now(),
        ]);

        $this->etlRun->markFailed($message);
        $this->logError($message);

        return $step;
    }

    public function progress(int $progressPercent, ?string $message = null): void
    {
        $this->etlRun->updateProgress($progressPercent, $message);

        if (!blank($message)) {
            $this->logInfo($message);
        }
    }

    public function setSource(?string $sourceType, ?string $sourceName = null, ?string $sourceUri = null): void
    {
        $this->etlRun->update([
            'source_type' => $sourceType,
            'source_name' => $sourceName,
            'source_uri'  => $sourceUri,
        ]);
    }

    public function done(?array $summary = null): void
    {
        $summary ??= $this->buildSummary();

        $this->completeStep('finalize', 'Import finalized.');
        $this->etlRun->markCompleted($summary);
        $this->logSuccess('Import completed successfully.');
    }

    public function failed(string $message): void
    {
        $this->etlRun->markFailed($message);
        $this->logError($message);
    }

    public function cancel(string $message = 'Import cancelled.'): void
    {
        $this->etlRun->update([
            'status'           => EtlRunStatus::Cancelled,
            'current_step'     => 'cancelled',
            'current_message'  => $message,
            'finished_at'      => Carbon::now(),
        ]);

        $this->logWarning($message);
    }

    public function cancellationRequested(): bool
    {
        return $this->etlRun->fresh()->cancellationRequested();
    }

    public function logDebug(string $message, ?array $context = null): void
    {
        $this->writeToLog($message, EtlRunLogLevel::Debug, $context);
    }

    public function logInfo(string $message, ?array $context = null): void
    {
        $this->writeToLog($message, EtlRunLogLevel::Info, $context);
    }

    public function logSuccess(string $message, ?array $context = null): void
    {
        $this->writeToLog($message, EtlRunLogLevel::Success, $context);
    }

    public function logError($msg, ?array $context = null): void
    {
        $this->writeToLog((string) $msg, EtlRunLogLevel::Error, $context);
    }

    public function logWarning($msg, ?array $context = null): void
    {
        $this->writeToLog((string) $msg, EtlRunLogLevel::Warning, $context);
    }

    public function logProgress($msg): void
    {
        $this->writeToLog((string) $msg, EtlRunLogLevel::Info);
    }

    public function validationInfo(
        string $title,
        ?string $message = null,
        ?string $worksheetName = null,
        ?int $rowNumber = null,
        ?string $columnName = null,
        ?string $cellCoordinate = null,
        ?string $code = null,
        ?array $context = null,
    ): EtlRunValidationMessage {
        return $this->validationMessage(
            EtlRunValidationSeverity::Info,
            $title,
            $message,
            $worksheetName,
            $rowNumber,
            $columnName,
            $cellCoordinate,
            $code,
            $context,
        );
    }

    public function validationWarning(
        string $title,
        ?string $message = null,
        ?string $worksheetName = null,
        ?int $rowNumber = null,
        ?string $columnName = null,
        ?string $cellCoordinate = null,
        ?string $code = null,
        ?array $context = null,
    ): EtlRunValidationMessage {
        $validationMessage = $this->validationMessage(
            EtlRunValidationSeverity::Warning,
            $title,
            $message,
            $worksheetName,
            $rowNumber,
            $columnName,
            $cellCoordinate,
            $code,
            $context,
        );

        $this->logWarning($title, [
            'message'         => $message,
            'worksheet_name'  => $worksheetName,
            'row_number'      => $rowNumber,
            'column_name'     => $columnName,
            'cell_coordinate' => $cellCoordinate,
            'code'            => $code,
        ]);

        return $validationMessage;
    }

    public function validationError(
        string $title,
        ?string $message = null,
        ?string $worksheetName = null,
        ?int $rowNumber = null,
        ?string $columnName = null,
        ?string $cellCoordinate = null,
        ?string $code = null,
        ?array $context = null,
    ): EtlRunValidationMessage {
        $validationMessage = $this->validationMessage(
            EtlRunValidationSeverity::Error,
            $title,
            $message,
            $worksheetName,
            $rowNumber,
            $columnName,
            $cellCoordinate,
            $code,
            $context,
        );

        $this->logError($title, [
            'message'         => $message,
            'worksheet_name'  => $worksheetName,
            'row_number'      => $rowNumber,
            'column_name'     => $columnName,
            'cell_coordinate' => $cellCoordinate,
            'code'            => $code,
        ]);

        return $validationMessage;
    }

    public function startProcessResult(
        string $worksheetName,
        ?string $processName = null,
        ?string $processType = null,
        ?string $category = null,
        ?string $message = null,
    ): EtlRunProcessResult {
        $processResult = EtlRunProcessResult::query()->create([
            'etl_run_id'     => $this->etlRun->id,
            'worksheet_name' => $worksheetName,
            'process_name'   => $processName ?? $worksheetName,
            'process_type'   => $processType,
            'category'       => $category,
            'status'         => EtlRunProcessResultStatus::Created,
            'message'        => $message,
            'started_at'     => Carbon::now(),
        ]);

        $this->logInfo("Started processing worksheet {$worksheetName}.");

        return $processResult;
    }

    public function updateProcessResult(
        EtlRunProcessResult $processResult,
        array $counts = [],
        ?string $message = null,
        ?EtlRunProcessResultStatus $status = null,
    ): EtlRunProcessResult {
        $data = [];

        foreach ($this->processResultCountKeys() as $key) {
            if (array_key_exists($key, $counts)) {
                $data[$key] = max(0, (int) $counts[$key]);
            }
        }

        if (!is_null($message)) {
            $data['message'] = $message;
        }

        if (!is_null($status)) {
            $data['status'] = $status;
        }

        if ($data !== []) {
            $processResult->update($data);
        }

        return $processResult->refresh();
    }

    public function incrementProcessResult(
        EtlRunProcessResult $processResult,
        string $column,
        int $amount = 1,
    ): EtlRunProcessResult {
        if (!in_array($column, $this->processResultCountKeys(), true)) {
            return $processResult;
        }

        $processResult->increment($column, $amount);

        return $processResult->refresh();
    }

    public function addProcessResultEntity(
        EtlRunProcessResult $processResult,
        string $entityName,
        ?Entity $entity = null,
        ?int $rowNumber = null,
        string $role = 'output',
        string $status = 'created',
    ): EtlRunProcessResultEntity {
        $resultEntity = EtlRunProcessResultEntity::query()->create([
            'etl_run_process_result_id' => $processResult->id,
            'entity_id'                 => $entity?->id,
            'entity_name'               => $entityName,
            'row_number'                => $rowNumber,
            'role'                      => $role,
            'status'                    => $status,
        ]);

        $processResult->increment('sample_count');

        return $resultEntity;
    }

    public function finishProcessResult(
        EtlRunProcessResult $processResult,
        ?EtlRunProcessResultStatus $status = null,
        ?string $message = null,
        array $counts = [],
    ): EtlRunProcessResult {
        $data = [
            'status'      => $status ?? $processResult->status,
            'message'     => $message ?? $processResult->message,
            'finished_at' => Carbon::now(),
        ];

        foreach ($this->processResultCountKeys() as $key) {
            if (array_key_exists($key, $counts)) {
                $data[$key] = max(0, (int) $counts[$key]);
            }
        }

        $processResult->update($data);

        $this->logSuccess("Finished processing worksheet {$processResult->worksheet_name}.");

        return $processResult->refresh();
    }

    public function buildSummary(): array
    {
        return [
            'activities'        => $this->etlRun->n_activities ?? 0,
            'activity_attrs'    => $this->etlRun->n_activity_attributes ?? 0,
            'entities'          => $this->etlRun->n_entities ?? 0,
            'entity_attrs'      => $this->etlRun->n_entity_attributes ?? 0,
            'sheets'            => $this->etlRun->n_sheets ?? 0,
            'sheets_processed'  => $this->etlRun->n_sheets_processed ?? 0,
            'files'             => $this->etlRun->n_files ?? 0,
            'files_not_found'   => $this->etlRun->n_files_not_found ?? 0,
            'columns'           => $this->etlRun->n_columns ?? 0,
            'columns_skipped'   => $this->etlRun->n_columns_skipped ?? 0,
            'warnings'          => $this->etlRun->validationMessages()
                                                ->where('severity', EtlRunValidationSeverity::Warning->value)
                                                ->count(),
            'errors'            => $this->etlRun->validationMessages()
                                                ->where('severity', EtlRunValidationSeverity::Error->value)
                                                ->count(),
            'process_results'   => $this->etlRun->processResults()->count(),
        ];
    }

    private function getStep(string $key): EtlRunStep
    {
        return EtlRunStep::query()->firstOrCreate(
            [
                'etl_run_id' => $this->etlRun->id,
                'key'        => $key,
            ],
            [
                'label'      => Str::of($key)->replace('_', ' ')->title()->__toString(),
                'status'     => EtlRunStepStatus::Waiting,
                'sort_order' => 999,
            ],
        );
    }

    private function statusForStep(string $key): EtlRunStatus
    {
        return match ($key) {
            'queued'             => EtlRunStatus::Queued,
            'read_spreadsheet'   => EtlRunStatus::Reading,
            'parse_worksheets'   => EtlRunStatus::Parsing,
            'validate'           => EtlRunStatus::Validating,
            'process_worksheets' => EtlRunStatus::Processing,
            'finalize'           => EtlRunStatus::Finalizing,
            default              => EtlRunStatus::Processing,
        };
    }

    private function validationMessage(
        EtlRunValidationSeverity $severity,
        string $title,
        ?string $message = null,
        ?string $worksheetName = null,
        ?int $rowNumber = null,
        ?string $columnName = null,
        ?string $cellCoordinate = null,
        ?string $code = null,
        ?array $context = null,
    ): EtlRunValidationMessage {
        return EtlRunValidationMessage::query()->create([
            'etl_run_id'      => $this->etlRun->id,
            'severity'        => $severity,
            'code'            => $code,
            'worksheet_name'  => $worksheetName,
            'row_number'      => $rowNumber,
            'column_name'     => $columnName,
            'cell_coordinate' => $cellCoordinate,
            'title'           => $title,
            'message'         => $message,
            'context'         => $context,
        ]);
    }

    private function writeToLog(string $message, EtlRunLogLevel $level, ?array $context = null): void
    {
        $this->writeToFilesystemLog($message, $level);

        EtlRunLogEntry::query()->create([
            'etl_run_id' => $this->etlRun->id,
            'level'      => $level,
            'message'    => trim($message),
            'context'    => $context,
        ]);
    }

    private function writeToFilesystemLog(string $message, EtlRunLogLevel $level): void
    {
        // Preserve indentation behavior from the existing filesystem log.
        $indentSize = strlen($message) - strlen(ltrim($message));

        $prefix = match ($level) {
            EtlRunLogLevel::Error => 'Error: ',
            EtlRunLogLevel::Warning => 'Warning: ',
            default => '',
        };

        $str = Str::of($message)
                  ->ltrim()
                  ->prepend($prefix)
                  ->__toString();

        for ($i = 0; $i < $indentSize; $i++) {
            $str = ' '.$str;
        }

        Storage::disk('mcfs')->append("etl_logs/{$this->etlRun->uuid}.log", $str);
    }

    private function processResultCountKeys(): array
    {
        return [
            'sample_count',
            'input_count',
            'output_count',
            'activity_count',
            'attribute_count',
            'measurement_count',
            'file_count',
            'warning_count',
            'error_count',
        ];
    }
}
