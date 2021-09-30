<?php

namespace App\Mail\Datasets;

use App\Models\Dataset;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PublishedDatasetReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    private Dataset $dataset;
    private User $user;

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
        return $this->subject("Completed publishing dataset {$this->dataset->name}")
                    ->view('email.datasets.published-dataset-ready', [
                        'dataset' => $this->dataset,
                        'user'    => $this->user,
                    ]);
    }
}
