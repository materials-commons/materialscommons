<x-show-standard-details :item="$project">
    <a class="ml-3 fs-10" href="{{route('projects.users.index', [$project])}}">
        {{$project->team->members->count()}} @choice("Member|Members", $project->team->members->count())
    </a>
    <a class="ml-3 fs-10" href="{{route('projects.users.index', [$project])}}">
        {{$project->team->admins->count()}} @choice("Admin|Admins", $project->team->admins->count())
    </a>
    <span class="ml-3 fs-10 grey-5">Size: {{formatBytes($project->size)}}</span>
    <span class="ml-3 fs-10 grey-5">Slug: {{$project->slug}}</span>
</x-show-standard-details>

<form>
    <div class="form-group">
        <label for="counts">Counts</label>
        <ul class="list-inline">
            <li class="list-inline-item mt-1">
                <span class="fs-10 grey-5">Files ({{$project->file_count}})</span>
            </li>
            <li class="list-inline-item mt-1">
                <span class="fs-10 grey-5">Directories ({{$project->directory_count}})</span>
            </li>
            <li class="list-inline-item mt-1">
                <span class="fs-10 grey-5">Samples ({{$project->entities_count}})</span>
            </li>
            <li class="list-inline-item mt-1">
                <span class="fs-10 grey-5">Published Datasets ({{$project->published_datasets_count}})
                </span>
            </li>
            <li class="list-inline-item mt-1">
                <span class="fs-10 grey-5">Unpublished Datasets ({{$project->unpublished_datasets_count}})
                </span>
            </li>
            <li class="list-inline-item mt-1">
                <span class="fs-10 grey-5">Experiments ({{$project->experiments_count}})</span>
            </li>
        </ul>
    </div>
</form>
@include('partials.overview._overview')

<x-display-markdown-file :file="$readme"></x-display-markdown-file>
