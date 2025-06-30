<?php

namespace App\Jobs\Folders;

use App\Helpers\PathHelpers;
use App\Models\File;
use App\Models\Project;
use App\Traits\GetProjectFiles;
use App\Traits\PathForFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class CreateFolderZipJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use PathForFile;
    use GetProjectFiles;

    public $timeout = 3600; // 1 hour timeout
    public $tries = 1;

    private $projectId;
    private $folderId;
    private $userId;
    private $zipId;

    /**
     * Create a new job instance.
     *
     * @param int $projectId
     * @param int $folderId
     * @param int $userId
     * @param string $zipId
     */
    public function __construct($projectId, $folderId, $userId, $zipId)
    {
        $this->projectId = $projectId;
        $this->folderId = $folderId;
        $this->userId = $userId;
        $this->zipId = $zipId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        umask(0);

        // Initialize progress in cache
        Cache::put("zip_progress_{$this->zipId}", [
            'status' => 'processing',
            'progress' => 0,
            'total_files' => 0,
            'processed_files' => 0,
            'zip_path' => null,
        ], 3600); // Cache for 1 hour

        $project = Project::findOrFail($this->projectId);
        $folder = $project->folders()->findOrFail($this->folderId);
        
        // Create directory for zip files if it doesn't exist
        $zipDir = Storage::disk('mcfs')->path("zip_files/{$this->userId}");
        if (!file_exists($zipDir)) {
            mkdir($zipDir, 0777, true);
        }
        
        $zipFilePath = "{$zipDir}/{$folder->name}_{$this->zipId}.zip";
        
        // Count total files for progress tracking
        $files = $this->getFilesInFolder($project, $folder);
        $totalFiles = count($files);
        
        // Update cache with total files
        Cache::put("zip_progress_{$this->zipId}", [
            'status' => 'processing',
            'progress' => 0,
            'total_files' => $totalFiles,
            'processed_files' => 0,
            'zip_path' => $zipFilePath,
        ], 3600);
        
        // Create zip file
        $zip = new ZipArchive();
        $zip->open($zipFilePath, ZipArchive::CREATE) or die("Could not open archive");
        
        $processedFiles = 0;
        $maxFileCountBeforeReopen = 200;
        
        foreach ($files as $file) {
            if ($zip->numFiles == $maxFileCountBeforeReopen) {
                $zip->close();
                $zip->open($zipFilePath) or die("Error: Could not reopen Zip");
            }
            
            $uuidPath = $this->getFilePathForFile($file);
            $fullPath = Storage::disk('mcfs')->path("{$uuidPath}");
            
            // Calculate relative path from folder
            $relativePath = $this->getRelativePath($folder, $file);
            $pathInZipfile = PathHelpers::normalizePath("{$folder->name}/{$relativePath}");
            
            $zip->addFile($fullPath, $pathInZipfile);
            
            $processedFiles++;
            $progress = ($processedFiles / $totalFiles) * 100;
            
            // Update progress in cache every 10 files or at the end
            if ($processedFiles % 10 === 0 || $processedFiles === $totalFiles) {
                Cache::put("zip_progress_{$this->zipId}", [
                    'status' => 'processing',
                    'progress' => $progress,
                    'total_files' => $totalFiles,
                    'processed_files' => $processedFiles,
                    'zip_path' => $zipFilePath,
                ], 3600);
            }
        }
        
        $zip->close();
        
        // Mark as completed in cache
        Cache::put("zip_progress_{$this->zipId}", [
            'status' => 'completed',
            'progress' => 100,
            'total_files' => $totalFiles,
            'processed_files' => $totalFiles,
            'zip_path' => $zipFilePath,
        ], 3600);
    }
    
    /**
     * Get all files in a folder and its subfolders
     *
     * @param Project $project
     * @param $folder
     * @return array
     */
    private function getFilesInFolder(Project $project, $folder)
    {
        $files = [];
        
        // Get direct files in this folder
        $files = array_merge($files, $folder->currentFiles()->get()->all());
        
        // Get files in subfolders recursively
        foreach ($folder->folders as $subfolder) {
            $files = array_merge($files, $this->getFilesInFolder($project, $subfolder));
        }
        
        return $files;
    }
    
    /**
     * Get the relative path of a file from a folder
     *
     * @param $folder
     * @param File $file
     * @return string
     */
    private function getRelativePath($folder, File $file)
    {
        $folderPath = $folder->path;
        $filePath = $file->directory->path;
        
        // If file is directly in the folder
        if ($filePath === $folderPath) {
            return $file->name;
        }
        
        // Remove the folder path from the file path to get the relative path
        if (Str::startsWith($filePath, $folderPath)) {
            $relativePath = substr($filePath, strlen($folderPath));
            if (Str::startsWith($relativePath, '/')) {
                $relativePath = substr($relativePath, 1);
            }
            return $relativePath . '/' . $file->name;
        }
        
        // Fallback
        return $file->name;
    }
}