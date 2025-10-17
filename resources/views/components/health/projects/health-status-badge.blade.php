@props(['project'])
@if($project->health == 'critical')
    <span class="ms-2 badge badge-danger"><i class="fas fa-exclamation-triangle"></i>See Health Report</span>
@elseif($project->health == 'warning')
    <span class="ms-2 badge badge-warning"><i class="fas fa-exclamation-circle"></i>See Health Report</span>
@endif
