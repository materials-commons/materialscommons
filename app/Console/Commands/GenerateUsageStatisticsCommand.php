<?php

namespace App\Console\Commands;

use App\Mail\MaterialsCommonsMonthlyStatisticsMail;
use App\Models\Dataset;
use App\Models\File;
use App\Models\Project;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class GenerateUsageStatisticsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc:generate-usage-statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates various usage statistics about Materials Commons';

    private $oneMonthAgo;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->oneMonthAgo = Carbon::now()->subMonth()->toDateString();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userStats = $this->collectUserStatistics();
        $projectStats = $this->collectProjectStatistics();
        $dsStats = $this->collectPublishedDatasetStatistics();
        $fileStats = $this->collectFileStatistics();
        $spreadsheetStats = $this->collectSpreadsheetLoadStatistics();
        $mail = new MaterialsCommonsMonthlyStatisticsMail($userStats, $projectStats, $dsStats, $fileStats,
            $spreadsheetStats);
        $this->info("Emailing Usage Statistics...");
        Mail::to("gtarcea@umich.edu")->send($mail);
        return 0;
    }

    private function collectUserStatistics(): \stdClass
    {
        $this->info("Collecting User Statistics...");
        $userStats = new \stdClass();
        $userStats->numberOfJoinedOverLastMonth = User::where('created_at', '>', $this->oneMonthAgo)
                                                      ->count();
        $userStats->totalNumberOfUsers = User::all()->count();
        return $userStats;
    }

    private function collectProjectStatistics(): \stdClass
    {
        $this->info("Collecting Project Statistics...");
        $projectStats = new \stdClass();

        $projectStats->numberOfProjectsCreatedOverLastMonth = Project::where('created_at', '>', $this->oneMonthAgo)
                                                                     ->count();
        $projectStats->totalNumberOfProjects = Project::all()->count();
        return $projectStats;
    }

    private function collectPublishedDatasetStatistics(): \stdClass
    {
        $this->info("Collecting Published Dataset Statistics...");
        $dsStats = new \stdClass();
        $dsStats->numberOfDatasetsPublishedOverLastMonth = Dataset::where('published_at', '>', $this->oneMonthAgo)
                                                                  ->count();
        $dsStats->totalNumberOfPublishedDatasets = Dataset::whereNotNull('published_at')->count();
        $dsStats->publishedDatasets = Dataset::where('published_at', '>', $this->oneMonthAgo)->get();

        return $dsStats;
    }

    private function collectFileStatistics(): \stdClass
    {
        $this->info("Collecting File Statistics...");
        $fileStats = new \stdClass();
        $fileStats->numberOfFilesAddedOverLastMonth = File::where('created_at', '>', $this->oneMonthAgo)
                                                          ->where('size', '>', 0)
                                                          ->whereNotNull('project_id')
                                                          ->count();
        $fileStats->bytesAddedOverLastMonth = File::where('created_at', '>', $this->oneMonthAgo)
                                                  ->where('size', '>', 0)
                                                  ->whereNotNull('project_id')
                                                  ->sum('size');
        $fileStats->totalNumberOfFiles = File::where('size', '>', 0)
                                             ->whereNotNull('project_id')
                                             ->count();
        $fileStats->totalBytes = File::where('size', '>', 0)
                                     ->whereNotNull('project_id')
                                     ->sum('size');
        return $fileStats;
    }

    private function collectSpreadsheetLoadStatistics(): \stdClass
    {
        $this->info("Collecting Spreadsheet Load Statistics...");
        $spreadsheetStats = new \stdClass();

        return $spreadsheetStats;
    }
}
