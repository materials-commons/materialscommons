@if(!blank($papers))
    <div class="mb-3">
        <label for="dataset-papers">Papers</label>
        <ul class="list-unstyled ms-3" id="dataset-papers">
            @foreach($papers as $paper)
                @if($loop->odd)
                    <li style="border-left: 2px solid #3182ce" class="mb-3">
                        <div class="ms-2">
                            <h6 class="fs-11 fw-500">{{$paper->name}}</h6>
                            <div class="fs-10 fw-500">{{$paper->reference}}</div>
                            @if(!is_null($paper->url))
                                <a href="{{$paper->url}}" target="_blank" class="reference-link">
                                    <i class="fa fas fa-fw fa-external-link-alt"></i> {{$paper->url}}
                                </a>
                            @endif
                        </div>
                    </li>
                @else
                    <li style="border-left: 2px solid hsl(209, 14%, 37%)" class="mb-3">
                        <div class="ms-2">
                            <h6 class="fs-11 fw-500">{{$paper->name}}</h6>
                            <div class="fs-10 fw-500">{{$paper->reference}}</div>
                            @if(!is_null($paper->url))
                                <a href="{{$paper->url}}" target="_blank" class="reference-link">
                                    <i class="fa fas fa-fw fa-external-link-alt"></i> {{$paper->url}}
                                </a>
                            @endif
                        </div>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
@endif
