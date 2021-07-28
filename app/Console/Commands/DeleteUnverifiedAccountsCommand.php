<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DeleteUnverifiedAccountsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:delete-unverified-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes unverified accounts that are older than 1 week.';

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
        $oneWeekAgo = Carbon::now()->subWeek()->toDateTimeString();
        $cursor = User::where('created_at', '<=', $oneWeekAgo)
                      ->whereNull('email_verified_at')
                      ->cursor();
        foreach ($cursor as $user) {
            $this->info("Deleting unverified user: {$user->name}/{$user->email}");
            $this->deleteUser($user);
        }
        return 0;
    }

    private function deleteUser(User $user)
    {
        try {
            $user->delete();
        } catch (\Exception $e) {
            $this->info("   Failed to delete use: {$user->name}/{$user->email}");
        }
    }
}
