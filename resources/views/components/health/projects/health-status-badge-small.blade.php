@props(['project'])
@if($project->health == 'critical')
    <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i></span>
@elseif($project->health == 'warning')
    <span class="badge badge-warning text-white"><i class="fas fa-exclamation-circle"></i></span>
@endif
