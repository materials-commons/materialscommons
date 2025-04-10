@props(['project', 'directory', 'scripts', 'arg'])
<div>
    @if(isInBeta('run_scripts'))
        @if($scripts->count() != 0)
            <a class="float-right action-link mr-4" data-toggle="modal" href="#select-script-dialog">
                <i class="fas fa-fw fa-play-circle mr-2"></i>Run Script
            </a>
        @endif
    @endif

    <a class="float-right action-link mr-4"
       href="{{route('projects.folders.index-images', [$project, $directory, 'arg' => $arg])}}">
        <i class="fas fa-fw fa-images mr-2"></i>View Images
    </a>

    <a class="float-right action-link mr-4"
       href="{{route('projects.folders.show', [$project, $directory, 'arg' => 'move-copy'])}}">
        <i class="fas fa-angle-double-right mr-2"></i> Move/Copy
    </a>

    <a class="float-right action-link mr-4"
       href="{{route('projects.folders.create', [$project, $directory, 'arg' => $arg])}}">
        <i class="fas fa-fw fa-folder-plus mr-2"></i>Create Directory
    </a>

    <a class="float-right action-link mr-4"
       href="{{route('projects.folders.upload', [$project->id, $directory->id, 'arg' => $arg])}}">
        <i class="fas fa-fw fa-plus mr-2"></i>Upload
    </a>
</div>