@if(isset($fileDescriptionTypes) && sizeof($fileDescriptionTypes) != 0)
    <div class="mb-3">
        <label for="file-types">File Types ({{sizeof($fileDescriptionTypes)}})</label>
        <ul class="list-inline">
            @foreach($fileDescriptionTypes as $type => $count)
                <li class="list-inline-item mt-1">
                    <span class="fs-10 grey-5">{{$type}} ({{$count}})</span>
                </li>
            @endforeach
        </ul>
    </div>
@endif
