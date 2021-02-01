<?php

namespace App\Console\Commands\Globus;

use App\Actions\Globus\ImportGlobusFilesIntoProjectAction;
use App\Jobs\Globus\ImportGlobusFilesJob;
use App\Models\GlobusRequest;
use Illuminate\Console\Command;

class ImportFinishedGlobusRequestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-globus:process-finished-globus-requests {--background} {--dry-run} {--log}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process finished globus requests';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        if ($this->option('dry-run')) {
            $this->performDryRun();
            return 0;
        }

        $this->processFinishedRequests();

        return 0;
    }

    private function performDryRun()
    {
        $this->table(
            ['ID', 'Project', 'Who'],
            $this->getFinishedGlobusRequestsQuery()
                 ->get()
                 ->map(function ($g) {
                     return [
                         'id'      => $g->id,
                         'project' => $g->project->name,
                         'owner'   => $g->owner->name,
                     ];
                 })
                 ->toArray()
        );
    }

    private function processFinishedRequests()
    {
        $background = $this->option('background');
        foreach ($this->getFinishedGlobusRequestsQuery()->cursor() as $finishedRequest) {
            if ($background) {
                ImportGlobusFilesJob::dispatch($finishedRequest)->onQueue('globus');
            } else {
                $importFilesAction = new ImportGlobusFilesIntoProjectAction();
                $importFilesAction->execute($finishedRequest);
            }
        }
    }

    private function getFinishedGlobusRequestsQuery(): GlobusRequest
    {
        return GlobusRequest::where('state', 'unmounted')
                            ->whereNotIn('project_id', function ($q) {
                                $q->select('project_id')
                                  ->from('globus_requests')
                                  ->where('state', 'loading');
                            });
    }
}
