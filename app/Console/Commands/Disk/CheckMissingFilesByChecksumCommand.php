<?php

namespace App\Console\Commands\Disk;

use App\Models\File;
use Illuminate\Console\Command;

class CheckMissingFilesByChecksumCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mc-disk:check-missing-files-by-checksum {missing_files_file : file with missing files}';

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
        $missing_files_file = $this->argument('missing_files_file');
        $this->info("Missing files file: {$missing_files_file}");
        $handle = fopen($missing_files_file, "r");
        $handle2 = fopen("/tmp/missing-permanently", "w");
        while (($line = fgets($handle)) !== false) {
           $line = trim($line);
           $parts = explode(":", $line);
           $fileId = $parts[1];
           $f = File::with('directory')->findOrFail($fileId);
           echo "Checking {$line}\n";
           if ($f->mime_type === 'url') {
               echo "  Skipping url file\n";
               continue;
           }
           if (blank($f->checksum)) {
               echo "  Skipping file with no checksum its gone...\n";
               fwrite($handle2, "{$line}\n");
               continue;
           }
           $files = File::where('checksum', $f->checksum)->get();
           $foundUsingChecksum = false;
           echo "  Found this many matches on checksum: " . count($files) . "\n";
           foreach ($files as $file) {
               if ($file->realFileExists()) {
                   $foundUsingChecksum = true;
                   break;
               }
           }
           if (!$foundUsingChecksum) {
               echo "Missing permanently: {$f->directory->path}/{$f->name}:{$f->id}:{$f->project_id}:{$f->checksum}\n";
               fwrite($handle2, "{$line}:{$f->checksum}\n");
           } else {
               echo "  Found using checksum\n";
           }
        }
        fclose($handle);
        fclose($handle2);
    }
}
