<?php

namespace App\Mail;

use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserAddedToProjectMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var \App\Models\Project */
    public $project;

    /** @var \App\Models\User */
    public $addingUser;

    /** @var \App\Models\User */
    public $user;

    public function __construct(Project $project, User $user, User $addingUser)
    {
        $this->project = $project;
        $this->user = $user;
        $this->addingUser = $addingUser;
    }

    public function build()
    {
        $this->project->load('owner');
        return $this->subject('Added to Materials Commons Project')
                    ->view('email.project.user_added');
    }
}
