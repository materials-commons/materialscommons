<?php

namespace App\Console\Commands\Disk;

use App\Models\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RestoreMissingFilesFromFilesNotInDbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-disk:restore-missing-files-from-files-not-in-db {missingFiles : list of missing files} {notFoundFiles : list of files not found}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set("memory_limit", "4096M");
        $missingFilesPath = $this->argument('missingFiles');
        $notFoundFilesPath = $this->argument('notFoundFiles');

        $missingHandle = fopen($missingFilesPath, "r");
        $notFoundHandle = fopen($notFoundFilesPath, "r");
        $filesBySize = collect();
        while (($line = fgets($notFoundHandle)) !== false) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }
            [$uuid, $size] = explode(":", $line, 2);
            $size = (int) $size;
            if (!$filesBySize->has($size)) {
                $filesBySize->put($size, collect());
            }
            $filesBySize->get($size)->push($uuid);
        }

        $potentialFilesWithMatches = 0;
        while(($line = fgets($missingHandle)) !== false) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

//            echo "Checking {$line}\n";
            try {
                [$path, $fileId, $uuid, $usesUuid, $createdAt, $projectId, $checksum] = explode(":", $line, 7);
            } catch (\Exception $e) {
                echo "ERROR: parsing line {$line}\n";
                [$path, $fileId, $uuid, $usesUuid, $createdAt, $projectId] = explode(":", $line, 6);
                $f = File::where('id', $fileId)->first();
                if (!is_null($f)) {
                    if (blank($f->checksum)) {
                        echo "ERROR: File {$f->name}/{$f->id} has no checksum\n";
                    }
                    if (($f->size == 0)) {
                        echo "ERROR: File {$f->name}/{$f->id} has size 0\n";
                    }
                }
                continue;
            }
            $file = File::where('id', $fileId)->first();
            if (is_null($file)) {
                continue;
            }

            if ($filesBySize->has($file->size)) {
                $files = $filesBySize->get($file->size);
                foreach($files as $fuuid) {
                    $p = Storage::disk('mcfs')->path($this->getUuidPathPartial($fuuid));
                    echo "Checking {$p}\n";
                    $checksum = md5_file($p);
                    if ($checksum == $file->checksum) {
                        echo "Could restore {$file->name}/{$file->id} with size {$file->size} from {$p}\n";
                        break;
                    }
                }
//                echo "File {$file->name}/{$file->id} with size {$file->size} has {$files->count()} potential matches\n";
//                echo ":size:{$file->size}:\n";
                $potentialFilesWithMatches++;
            }
        }

        echo "Found {$potentialFilesWithMatches} potential files we can look for matching checksums\n";
    }

    private function getUuidPathPartial($uuid)
    {
        $entries = explode('-', $uuid);
        $entry1 = $entries[1];

        return "{$entry1[0]}{$entry1[1]}/{$entry1[2]}{$entry1[3]}/{$uuid}";
    }
}
