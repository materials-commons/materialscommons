<?php

namespace App\Jobs\Datasets;

use App\Mail\Datasets\PublishedDatasetUpdatedMail;
use App\Models\Dataset;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPublishedDatasetUpdatedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Dataset $dataset;
    public User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
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
        $mail = New PublishedDatasetUpdatedMail($this->dataset, $this->user);
        Mail::to($this->user)->send($mail);
    }
}
