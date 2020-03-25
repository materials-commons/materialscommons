<?php

namespace App\Console\Commands;

use App\Mail\AnnouncementMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAnnouncementEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:send-announcement-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the announcement email about Materials Commons';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set("memory_limit", "4096M");

//        $user = User::where('email', 'gtarcea@umich.edu')->first();
//        Mail::bcc($user->email)->send(new AnnouncementMail($user));

        $this->sendEmail("gtarcea@umich.edu");
        $this->sendEmail("johnea@umich.edu");
        $this->sendEmail("bpuchala@umich.edu");
        $this->sendEmail("tradiasa@umich.edu");

//        DB::table("users")->orderBy('email')->chunk(100, function($users) {
//            foreach($users as $user) {
//                echo "Sending email to {$user->email}\n";
//                Mail::bcc($user->email)->send(new AnnouncementMail($user));
//            }
//        });
    }

    private function sendEmail($emailAddress)
    {
        echo "Sending annoucement to: {$emailAddress}\n";
        $user = User::where('email', $emailAddress)->first();
        Mail::bcc($user->email)->send(new AnnouncementMail($user));
    }
}
