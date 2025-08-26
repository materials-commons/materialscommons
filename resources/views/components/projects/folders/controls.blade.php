@props(['project', 'directory', 'scripts', 'arg', 'destdir', 'destproj'])
<div class="bg-white border rounded p-3 mb-4 shadow-sm">
    @if(isInBeta('run_scripts'))
        @if($scripts->count() != 0)
            <a class="float-right action-link mr-4" data-toggle="modal" href="#select-script-dialog">
                <i class="fas fa-fw fa-play-circle mr-2"></i>Run Script
            </a>
        @endif
    @endif

    <a classx="float-rightx action-link mr-4"
       class="text-primaryx action-link text-decoration-none px-3 py-1
"

       href="{{route('projects.folders.index-images', [$project, $directory, 'destdir' => $destdir, 'destproj' => $destproj, 'arg' => $arg])}}">
        <i class="fas fa-fw fa-images mr-2"></i>View Images
    </a>
        <span class="text-muted">|</span>

    <a class="float-rightx action-link mr-4"
       href="{{route('projects.folders.show', [$project, $directory, 'destdir' => $destdir, 'destproj' => $destproj, 'arg' => 'move-copy'])}}">
        <i class="fas fa-angle-double-right mr-2"></i> Move/Copy
    </a>

    <a class="float-rightx action-link mr-4"
       href="{{route('projects.folders.create', [$project, $directory, 'destdir' => $destdir, 'destproj' => $destproj, 'arg' => $arg])}}">
        <i class="fas fa-fw fa-folder-plus mr-2"></i>Create Directory
    </a>

    <a class="float-rightx action-link mr-4"
       href="{{route('projects.folders.add-url', [$project, $directory, 'destdir' => $destdir, 'destproj' => $destproj, 'arg' => $arg])}}">
        <i class="fas fa-fw fa-link mr-2"></i>Add URL
    </a>

    <a class="float-rightx action-link mr-4"
       href="{{route('projects.folders.upload', [$project->id, $directory->id, 'destdir' => $destdir, 'destproj' => $destproj, 'arg' => $arg])}}">
        <i class="fas fa-fw fa-plus mr-2"></i>Upload
    </a>
</div>
