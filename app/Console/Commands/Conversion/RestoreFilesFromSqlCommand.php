<?php

namespace App\Console\Commands\Conversion;

use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class RestoreFilesFromSqlCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-convert:restore-files {project : new project id}';

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
        $project = Project::findOrFail($this->argument("project"));
        $rootDirId = $project->rootDir->id;
        if ($file = fopen("/tmp/all_files.sql", "r")) {
            while (!feof($file)) {
                $textperline = fgets($file);
                $lines = explode("),(", $textperline);
                foreach ($lines as $line) {
                    if (Str::contains($line, "'directory'")) {
                        if (Str::contains($line, ",316,396,")) {
                            if (Str::startsWith($line, "108432,")) {
                                continue;
                            }

                            if (Str::contains($line, ",108432,")) {
                                $line = Str::replaceFirst(",108432,", ",{$rootDirId},", $line);
                            }

                            $line = Str::replaceFirst(",316,396,", ",316,{$project->id},", $line);

                            echo "INSERT INTO `files` VALUES ({$line});\n";
                        }
                    }
                }
            }
            fclose($file);
        }

        if ($file = fopen("/tmp/all_files.sql", "r")) {
            while (!feof($file)) {
                $textperline = fgets($file);
                $lines = explode("),(", $textperline);
                foreach ($lines as $line) {
                    if (Str::contains($line, "'directory'")) {
                        continue;
                    }
                    if (Str::contains($line, ",316,396,")) {
                        if (Str::startsWith($line, "108432,")) {
                            continue;
                        }

                        if (Str::contains($line, ",108432,")) {
                            $line = Str::replaceFirst(",108432,", ",{$rootDirId},", $line);
                        }

                        $line = Str::replaceFirst(",316,396,", ",316,{$project->id},", $line);

                        echo "INSERT INTO `files` VALUES ({$line});\n";
                    }
                }
            }
            fclose($file);
        }
        return 0;
    }
}
