@if(!blank($papers))
    <div class="form-group">
        <label for="dataset-papers">Papers</label>
        <ul class="list-unstyled ml-3" id="dataset-papers">
            @foreach($papers as $paper)
                @if($loop->odd)
                    <li style="border-left: 2px solid #3182ce" class="mb-4">
                        <div class="ml-2">
                            <h5>{{$paper->name}}</h5>
                            <p>{{$paper->reference}}</p>
                            @if(!is_null($paper->url))
                                <a href="{{$paper->url}}" target="_blank" class="reference-link">
                                    <i class="fa fas fa-fw fa-external-link-alt"></i> {{$paper->url}}
                                </a>
                            @endif
                        </div>
                    </li>
                @else
                    <li style="border-left: 2px solid hsl(209, 14%, 37%)" class="mb-2">
                        <div class="ml-2">
                            <h5>{{$paper->name}}</h5>
                            <p>{{$paper->reference}}</p>
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