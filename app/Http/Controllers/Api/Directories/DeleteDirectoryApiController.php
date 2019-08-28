<?php

namespace App\Http\Controllers\Api\Directories;

use App\Actions\Directories\DeleteDirectoryAction;
use App\Http\Controllers\Controller;

class DeleteDirectoryApiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  DeleteDirectoryAction  $deleteDirectoryAction
     * @param $directoryId
     *
     * @return void
     * @throws \Exception
     */
    public function __invoke(DeleteDirectoryAction $deleteDirectoryAction, $directoryId)
    {
        $directory = File::findOrFail($directoryId);
        $deleteDirectoryAction($directory);
    }
}
