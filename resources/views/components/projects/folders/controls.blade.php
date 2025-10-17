@props(['project', 'directory', 'scripts', 'arg', 'destdir', 'destproj'])
<div>
    @if(isInBeta('run_scripts'))
        @if($scripts->count() != 0)
            <a class="float-right action-link me-4" data-bs-toggle="modal" href="#select-script-dialog">
                <i class="fas fa-fw fa-play-circle me-2"></i>Run Script
            </a>
        @endif
    @endif

    <a class="float-right action-link me-4"
       href="{{route('projects.folders.index-images', [$project, $directory, 'destdir' => $destdir, 'destproj' => $destproj, 'arg' => $arg])}}">
        <i class="fas fa-fw fa-images me-2"></i>View Images
    </a>

    <a class="float-right action-link me-4"
       href="{{route('projects.folders.show', [$project, $directory, 'destdir' => $destdir, 'destproj' => $destproj, 'arg' => 'move-copy'])}}">
        <i class="fas fa-angle-double-right me-2"></i> Move/Copy
    </a>

    <a class="float-right action-link me-4"
       href="{{route('projects.folders.create', [$project, $directory, 'destdir' => $destdir, 'destproj' => $destproj, 'arg' => $arg])}}">
        <i class="fas fa-fw fa-folder-plus me-2"></i>Create Directory
    </a>

    <a class="float-right action-link me-4"
       href="{{route('projects.folders.add-url', [$project, $directory, 'destdir' => $destdir, 'destproj' => $destproj, 'arg' => $arg])}}">
        <i class="fas fa-fw fa-link me-2"></i>Add URL
    </a>

    <a class="float-right action-link me-4"
       href="{{route('projects.folders.upload', [$project->id, $directory->id, 'destdir' => $destdir, 'destproj' => $destproj, 'arg' => $arg])}}">
        <i class="fas fa-fw fa-plus me-2"></i>Upload
    </a>
</div>
