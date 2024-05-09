<?php

namespace App\Console\Commands\Admin;

use App\Models\BetaFeature;
use Illuminate\Console\Command;

class ShowBetaFeatureStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-admin:show-beta-feature-status {feature : feature name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show whether beta feature is active';

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

        if (is_null($feature->active_at)) {
            echo "{$feature->name} is not active\n";
        } else {
            echo "{$feature->name} is active\n";
        }

        return self::SUCCESS;
    }
}
