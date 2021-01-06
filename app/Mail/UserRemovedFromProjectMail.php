<?php

namespace App\Mail;

use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRemovedFromProjectMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var \App\Models\Project */
    public $project;

    /** @var \App\Models\User */
    public $removingUser;

    /** @var \App\Models\User */
    public $user;

    public function __construct(Project $project, User $user, User $removingUser)
    {
        $this->project = $project;
        $this->user = $user;
        $this->removingUser = $removingUser;
    }

    public function build()
    {
        $this->project->load('owner');
        return $this->subject('Removed from Materials Commons Project')
                    ->view('email.project.user_removed');
    }
}
