<?php

namespace App\Console\Commands\Admin;

use App\Models\BetaFeature;
use App\Models\User;
use Illuminate\Console\Command;
use function is_null;

class RemoveUserFromBetaFeatureCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-admin:remove-user-from-beta-feature {feature : feature name} {user : user id to add to feature}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove user from beta feature';

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

        $user = User::findOrFail($this->argument('user'));

        $feature->users()->detach($user);

        echo "Removed user {$user->email} from feature {$name}\n";

        return 0;
    }
}
