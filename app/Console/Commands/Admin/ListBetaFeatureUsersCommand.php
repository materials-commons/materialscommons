<?php

namespace App\Console\Commands\Admin;

use App\Models\BetaFeature;
use Illuminate\Console\Command;
use function is_null;

class ListBetaFeatureUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-admin:list-beta-feature-users {feature? : feature name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List beta feature users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('feature');

        if (blank($name)) {
            $this->listAllBetaFeaturesWithUsers();
            return 0;
        }

        $this->listSpecificBetaFeatureWithUsers($name);
        return 0;
    }

    public function listAllBetaFeaturesWithUsers()
    {
        BetaFeature::with(['users'])->each(function (BetaFeature $feature) {
            echo "Feature: {$feature->feature}\n";
            foreach ($feature->users as $user) {
                echo "  {$user->name}/{$user->email}\n";
            }
            echo "\n";
        });
    }

    public function listSpecificBetaFeatureWithUsers($name)
    {
        $feature = BetaFeature::with(['users'])
                              ->where('feature', $name)
                              ->first();
        if (is_null($feature)) {
            echo "No such feature {$name}\n";
            return;
        }

        echo "Feature: {$name}\n";
        foreach ($feature->users as $user) {
            echo "  {$user->name}/{$user->email}\n";
        }
    }
}
