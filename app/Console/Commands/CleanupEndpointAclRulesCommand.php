<?php

namespace App\Console\Commands;

use App\Actions\Globus\GlobusApi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CleanupEndpointAclRulesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:cleanup-endpoint-acl-rules';

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
        $globusApi = GlobusApi::createGlobusApi();
        $rules = $globusApi->getEndpointAccessRules(config('globus.endpoint'));
        foreach($rules['DATA'] as $rule) {
            if (Str::startsWith($rule['path'], ["/__published", "/__globus"])) {
                $this->info("Deleting ACL: {$rule['path']}/{$rule['id']}");
                $this->deleteAcl($rule, $globusApi);
            }
        }
        return 0;
    }

    private function deleteAcl($rule, GlobusApi $globusApi)
    {
        try {
            $globusApi->deleteEndpointAclRule(config('globus.endpoint'), $rule['id']);
        } catch (\Exception $e) {
            $this->info("  Unable to delete acl: {$e->getMessage()}");
        }
    }
}
