<?php

namespace App\Http\Middleware;

use App\Models\File;
use App\Traits\GetRequestParameterId;
use Closure;

class FileInProject
{
    use GetRequestParameterId;

    /**
     * Make sure that the file is in the project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $projectId = $this->getParameterId('project');
        if ($projectId == '') {
            return $next($request);
        }

        $fileId = $this->getFileId();
        if ($fileId == '') {
            return $next($request);
        }

        $count = File::where('id', $fileId)
                     ->where('project_id', $projectId)
                     ->count();
        abort_if($count == 0, 404, 'No such file');

        return $next($request);
    }

    private function getFileId()
    {
        $fileId = $this->getParameterId('file');
        if ($fileId != '') {
            return $fileId;
        }

        // Check if file id is under folder parameter
        $fileId = $this->getParameterId('folder');
        if ($fileId != '') {
            return $fileId;
        }

        // Lastly check if file id is under directory parameter
        $fileId = $this->getParameterId('directory');
        return $fileId;
    }
}
