@props(['file'])
@if($file->health == 'missing')
    <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i></span>
@elseif($file->health == 'duplicates')
    <span class="badge badge-warning text-white"><i class="fas fa-exclamation-circle"></i></span>
@elseif($file->health == 'warning')
    <span class="badge badge-warning text-white"><i class="fas fa-exclamation-circle"></i></span>
@endif
