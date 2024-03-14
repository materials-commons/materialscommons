<?php

namespace App\Console\Commands\Admin;

use App\Models\BetaFeature;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AddBetaFeatureCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-admin:add-beta-feature {feature : Feature name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds a new active beta feature';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $feature = BetaFeature::create([
            'feature'   => $this->argument('feature'),
            'active_at' => Carbon::now(),
        ]);

        echo "Created feature {$feature->feature} with id {$feature->id}\n";

        return 0;
    }
}
