<?php

namespace App\Console\Commands\Admin;

use App\Models\BetaFeature;
use App\Models\User;
use Illuminate\Console\Command;

class AddUserToBetaFeatureCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-admin:add-user-to-beta-feature {feature : feature name} {user : user email to add to feature}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add user to beta feature';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('feature');
        $feature = BetaFeature::where('feature', $name)->first();
        if (is_null($feature)) {
            echo "No such feature {$name}\n";
            return 1;
        }

        $user = User::where('email', $this->argument('user'))->first();
        if (is_null($user)) {
            $email = $this->argument('user');
            echo "No such user {$email}\n";
            return 1;
        }

        $feature->users()->syncWithoutDetaching($user);

        echo "Added user {$user->email} to feature {$name}\n";

        return 0;
    }
}
