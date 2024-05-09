<?php

namespace App\Mail\Datasets;

use App\Models\Dataset;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PublishedDatasetUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    private Dataset $dataset;
    private User $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Dataset $dataset, User $user)
    {
        $this->dataset = $dataset;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Published dataset on Materials Commons updated")
                    ->view('email.datasets.published-dataset-updated', [
                        'dataset' => $this->dataset,
                        'user'    => $this->user,
                    ]);
    }
}
