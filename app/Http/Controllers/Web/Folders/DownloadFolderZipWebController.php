<?php

namespace App\Http\Controllers\Web\Folders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadFolderZipWebController extends Controller
{
    public function __invoke(Request $request, $zipId)
    {
        // Get the zip file path from cache
        $progressData = Cache::get("zip_progress_{$zipId}");
        
        if (!$progressData || $progressData['status'] !== 'completed') {
            return back()->with('error', 'The zip file is not ready for download yet.');
        }
        
        $zipPath = $progressData['zip_path'];
        
        if (!file_exists($zipPath)) {
            return back()->with('error', 'The zip file could not be found.');
        }
        
        // Get the filename from the path
        $filename = basename($zipPath);
        
        // Return the file as a download
        return response()->download($zipPath, $filename);
    }
}