<?php

namespace App\Console\Commands\Disk;

use App\Models\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FindFilesWithoutDBEntryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-disk:find-files-without-db-entry';

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

        $outPath = "/home/gtarcea/files-not-in-db.txt";
        $handle = fopen($outPath, "w");

        $dirs = collect(Storage::disk('mcfs')->directories())->filter(function ($dir) {
            return Str::length($dir) == 2;
        });
        $processed = 0;
        foreach ($dirs as $dir) {
            $dirIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(Storage::disk('mcfs')->path($dir)),
                RecursiveIteratorIterator::SELF_FIRST);

            foreach ($dirIterator as $path => $finfo) {
                if ($finfo->isFile()) {
                    if (Str::contains($path, ".thumbnails")) {
                        continue;
                    }

                    if (Str::contains($path, ".conversion")) {
                        continue;
                    }

                    $processed++;

                    // If we are here then the file is a UUID name
                    $uuid = basename($path);
                    if ($processed % 10000 == 0) {
                        echo "Processed {$processed} files\n";
                    }

                    $file = File::where('uuid', $uuid)->first();

                    if (!is_null($file)) {
                        // file exists
                        continue;
                    }
                    $size = $finfo->getSize();
                    $content = "{$uuid}:{$size}\n";
                    fwrite($handle, $content);
                }
            }
        }
        fclose($handle);
    }
}
