<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SetUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:set-user-password {email : User email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $user = User::firstWhere('email', $this->argument('email'));
        if (is_null($user)) {
            $this->error('User not found!');
            return 0;
        }
        $password = $this->secret('New Password');
        $password2 = $this->secret('Retype Password');
        if ($password != $password2) {
            $this->error("Passwords don't match");
            return 0;
        }
        $user->password = Hash::make($password);
        $user->save();
        $this->info("User '{$user->name}' password updated");
        return 0;
    }
}
