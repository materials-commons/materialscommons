<?php

namespace App\Mail\Datasets;

use App\Models\Dataset;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnpublishDatasetCompleteMail extends Mailable
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
        return $this->view('email.datasets.unpublish-dataset-complete', [
            'dataset' => $this->dataset,
            'user'    => $this->user,
        ]);
    }
}
