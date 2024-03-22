<?php

namespace App\Jobs\Datasets;

use App\Actions\Datasets\RefreshPublishedDatasetAction;
use App\Models\Dataset;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RefreshPublishedDatasetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $dataset;
    public $user;

    public function __construct(Dataset $dataset, User $user)
    {
        $this->dataset = $dataset;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $refreshPublishedDatasetAction = new RefreshPublishedDatasetAction();
        $refreshPublishedDatasetAction->execute($this->dataset, $this->user);
    }
}
