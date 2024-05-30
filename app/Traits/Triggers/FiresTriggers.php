<?php

namespace App\Traits\Triggers;

use App\Models\File;
use App\Models\Project;
use App\Models\ScriptTrigger;
use App\Models\User;
use Illuminate\Support\Collection;

trait FiresTriggers
{
    private Collection $filesMatchingTriggerPath;
    private Collection $triggersByPath;
    private bool $populatedTriggers = false;

    public function __constructFireTriggers()
    {
        $this->filesMatchingTriggerPath = collect();
        $this->triggersByPath = collect();
    }

    public function trackForTriggers(File $file): void
    {
        $this->populateTriggers($file);

        $projectPath = $file->fullPath();
        if ($this->filesMatchingTriggerPath->contains($projectPath)) {
            $this->filesMatchingTriggerPath->put($projectPath, $file);
        }
    }

    private function populateTriggers(File $file): void
    {
        if (!$this->populatedTriggers) {
            $this->filesMatchingTriggerPath = collect();
            $this->triggersByPath = collect();

            $file->load('project');
            $triggers = ScriptTrigger::getProjectTriggers($file->project);
            $triggers->each(function (ScriptTrigger $trigger) {
                $this->filesMatchingTriggerPath->put($trigger->path, null);
                $this->triggersByPath->put($trigger->path, $trigger);
            });
            $this->populatedTriggers = true;
        }
    }

    private function loadTriggersForProject(Project $project): void
    {
        $triggers = ScriptTrigger::getProjectTriggers($project);
        $triggers->each(function (ScriptTrigger $trigger) {
            $this->triggersByPath->put($trigger->path, $trigger);
        });
    }

    private function pathIsTriggerPath(string $path): bool
    {
        return $this->triggersByPath->contains($path);
    }

    public function fireTriggers(User $user): void
    {
        $this->filesMatchingTriggerPath->each(function ($file, $pathKey) use ($user) {
            if (!is_null($file)) {
                // If $file isn't null then there was a match on a trigger
                $file->load('project');
                $trigger = $this->triggersByPath->get($pathKey);
                if ($trigger->fileWillActivateTrigger($file)) {
                    $trigger->execute($file->project, $file, $user);
                }
            }
        });
    }
}