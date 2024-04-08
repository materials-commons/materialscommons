<?php

namespace App\Console\Commands\Admin;

use App\Models\BetaFeature;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use function is_null;

class ToggleBetaFeatureActiveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-admin:toggle-beta-feature-active {feature : beta feature}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Toggle active status of beta feature';

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
            $feature->update(['active_at' => null]);
            echo "Set feature {$name} to inactive\n";
        } else {
            $feature->update(['active_at' => Carbon::now()]);
            echo "Set feature {$name} to active\n";
        }
    }
}
