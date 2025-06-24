<div class="mt-2xxx" style="width:100%">
    <ul class="list-group" style="z-index:999">
        <li class="list-group-item">
            <a hx-get="{{$searchRoute}}" style="cursor:pointer;" id="search-close"
               onclick="closeSearch()"
               hx-target="#search-results"
               class="float-right">
                <i class="fa fas fa-times mr-2"></i>close</a>
        </li>
        @foreach($searchResults as $sr)
            <li class="search-item-result">
                <a href="{{$sr->getScoutUrl()}}" class="list-group-item list-group-item-action search-item-result no-underline">
                    @if($sr->getTypeAttribute() === "file" || $sr->getTypeAttribute() == "directory")
                        <div class="d-flex w-100 justify-content-between">
                            @if($sr->directory->path === "/")
                                <h5 class="mb-1">
                                    {{$sr->directory->path}}{{$sr->name}}
                                </h5>
                            @else
                                <h5 class="mb-1">
                                    {{$sr->directory->path}}/{{$sr->name}}
                                </h5>
                            @endif
                        </div>
                    @else
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{$sr->name}}</h5>
                        </div>
                    @endif
                    <p class="mb-1">{{$sr->summary}}</p>
                    <small class="text-muted">Type: {{$sr->getTypeAttribute()}}</small>
                    @if(isset($sr->project))
                        <small class="text-muted ml-4">Project: {{$sr->project->name}}</small>
                    @endif
                    @if(isset($sr->experiments) && $sr->experiments->count() > 0)
                        <small class="text-muted ml-4">Experiments: {{$sr->experiments->map(fn($e) => $e->name)->implode(',')}}</small>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</div>