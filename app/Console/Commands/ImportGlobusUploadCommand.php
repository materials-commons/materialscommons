<?php

namespace App\Console\Commands;

use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\Uploads\LoadGlobusUploadIntoProjectAction;
use App\Enums\GlobusStatus;
use App\Models\GlobusUploadDownload;
use Illuminate\Console\Command;

class ImportGlobusUploadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:import-globus-upload {uploadId : Globus Upload Id}';

    const ProcessAllFilesInUpload = -1;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all the files and directories from a globus upload';

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
     * @return mixed
     */
    public function handle()
    {
        ini_set("memory_limit", "4096M");
        $upload = GlobusUploadDownload::findOrFail($this->argument("uploadId"));
        $upload->update(['status' => GlobusStatus::Loading]);
        $globusApi = GlobusApi::createGlobusApi();
        $loadGlobusUploadInProjectAction = new LoadGlobusUploadIntoProjectAction($upload, self::ProcessAllFilesInUpload,
            $globusApi);
        $loadGlobusUploadInProjectAction();
    }
}
