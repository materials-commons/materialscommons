<?php

namespace App\Mail;

use App\ViewModels\Emails\AnnouncementMailViewModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AnnouncementMail extends Mailable
{
    use Queueable, SerializesModels;

    private $viewModel;
    private $user;

    public function __construct($user)
    {
        $this->viewModel = new AnnouncementMailViewModel($user);
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $viewModel = new AnnouncementMailViewModel($this->user);
        return $this->subject('Materials Commons 2.0!')->view('email.announcement')->with('vm', $viewModel);
    }
}
