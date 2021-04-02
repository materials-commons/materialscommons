<?php

namespace App\Console\Commands\Conversion;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class MarkExistingAccountsAsVerifiedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-convert:mark-existing-accounts-as-verified';

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
        User::unguard();
        User::all()->each(function (User $user) {
            $this->info("Setting user {$user->name}/{$user->email} to verified");
            $user->update(['email_verified_at' => Carbon::now()]);
        });
        User::reguard();
        return 0;
    }
}
