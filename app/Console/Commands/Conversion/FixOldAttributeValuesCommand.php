<?php

namespace App\Console\Commands\Conversion;

use App\Models\AttributeValue;
use Illuminate\Console\Command;

class FixOldAttributeValuesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-convert:fix-old-attribute-values';

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
        ini_set("memory_limit", "4096M");
        $count = 0;
        $custom = 0;
        $compositionCount = 0;
        foreach (AttributeValue::with('attribute')->cursor() as $av) {
            if (is_array($av->val["value"])) {
                if ($av->attribute->name == 'Composition') {
                    echo "\nComposition:\n";
                    $compositionCount++;
//                    print_r($av->val["value"]);
                    foreach ($av->val["value"] as $entry) {
                        echo "  {$entry['element']} {$entry['value']} {$av->unit}\n";
                    }
                } elseif (!isset($av->val["value"]["name"])) {
                    $custom++;
//                    echo "id: {$av->id}\n";
//                    print_r($av->val["value"]);
                }
                $count++;
//                print_r($av->val["value"]);
            }
        }
        echo "\nTotal fixed: {$count}\n";
        echo "Total custom: {$custom}\n";
        echo "Total composition: {$compositionCount}\n";
        return 0;
    }
}
