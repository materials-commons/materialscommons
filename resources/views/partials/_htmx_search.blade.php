<div class="mt-2">
    <ul class="list-group" style="z-index:999">
        <li class="list-group-item">
            <a hx-get="{{$searchRoute}}" style="cursor:pointer;"
               onclick="closeSearch()"
               hx-target="#search-results"
               class="float-end">
                <i class="fa fas fa-times me-2"></i>close</a>
        </li>
        @foreach($searchResults->groupByType() as $type => $modelSearchResults)
            @foreach($modelSearchResults as $searchResult)
                <li class="search-item-result">
                    <a href="{{$searchResult->url}}" class="list-group-item list-group-item-action search-item-result">
                        @if($searchResult->searchable->type === "file" || $searchResult->searchable->type == "directory")
                            <div class="d-flex w-100 justify-content-between">
                                @if($searchResult->searchable->directory->path === "/")
                                    <h5 class="mb-1">
                                        {{$searchResult->searchable->directory->path}}{{$searchResult->title}}
                                    </h5>
                                @else
                                    <h5 class="mb-1">
                                        {{$searchResult->searchable->directory->path}}/{{$searchResult->title}}
                                    </h5>
                                @endif
                            </div>
                        @else
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{$searchResult->title}}</h5>
                            </div>
                        @endif
                        <p class="mb-1">{{$searchResult->searchable->summary}}</p>
                        <small class="text-muted">{{$searchResult->searchable->type}}</small>
                    </a>
                </li>
            @endforeach
        @endforeach
    </ul>
</div>
