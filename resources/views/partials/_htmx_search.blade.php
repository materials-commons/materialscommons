<div class="mt-2">
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Search Results for "{{ $search }}"</h5>
            <a hx-get="{{$searchRoute}}" style="cursor:pointer;"
               onclick="closeSearch()"
               hx-target="#search-results"
               class="btn btn-sm btn-outline-secondary">
                <i class="fa fas fa-times mr-2"></i>Close</a>
        </div>
        <div class="card-body p-0">
            @if($searchResults->count() > 0)
                @foreach($searchResults->groupByType() as $type => $modelSearchResults)
                    <div class="search-group mb-3">
                        <div class="search-group-header bg-light p-2 border-top border-bottom">
                            <h6 class="mb-0 text-capitalize">{{ $type }} ({{ count($modelSearchResults) }})</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach($modelSearchResults as $searchResult)
                                <li class="list-group-item search-item-result p-3 hover:bg-gray-50">
                                    <a href="{{$searchResult->url}}" class="text-decoration-none text-dark d-block">
                                        @if($searchResult->searchable->type === "file" || $searchResult->searchable->type == "directory")
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    @if($searchResult->searchable->type === "file")
                                                        <i class="fa fa-file text-primary"></i>
                                                    @else
                                                        <i class="fa fa-folder text-warning"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    @if($searchResult->searchable->directory->path === "/")
                                                        <h5 class="mb-1 font-weight-bold">
                                                            {{$searchResult->searchable->directory->path}}{{$searchResult->title}}
                                                        </h5>
                                                    @else
                                                        <h5 class="mb-1 font-weight-bold">
                                                            {{$searchResult->searchable->directory->path}}/{{$searchResult->title}}
                                                        </h5>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    @if($searchResult->searchable->type === "experiment")
                                                        <i class="fa fa-flask text-info"></i>
                                                    @elseif($searchResult->searchable->type === "dataset")
                                                        <i class="fa fa-database text-success"></i>
                                                    @elseif($searchResult->searchable->type === "activity")
                                                        <i class="fa fa-cogs text-secondary"></i>
                                                    @elseif($searchResult->searchable->type === "entity")
                                                        <i class="fa fa-cube text-danger"></i>
                                                    @elseif($searchResult->searchable->type === "workflow")
                                                        <i class="fa fa-sitemap text-primary"></i>
                                                    @elseif($searchResult->searchable->type === "community")
                                                        <i class="fa fa-users text-success"></i>
                                                    @else
                                                        <i class="fa fa-circle text-muted"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h5 class="mb-1 font-weight-bold">{{$searchResult->title}}</h5>
                                                </div>
                                            </div>
                                        @endif
                                        @if($searchResult->searchable->summary)
                                            <p class="mb-1 text-muted ml-4 pl-1">{{$searchResult->searchable->summary}}</p>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            @else
                <div class="p-4 text-center">
                    <p class="text-muted">No results found for "{{ $search }}"</p>
                </div>
            @endif
        </div>
    </div>
</div>
