<div class="mt-2">
    <h5>File Types ({{sizeof($fileDescriptionTypes)}}):</h5>
    <ul>
        @foreach($fileDescriptionTypes as $type => $count)
            @if($loop->iteration < 12)
                <li>{{$type}} ({{$count}})</li>
            @else
                <li class="hidden-file-type" hidden>{{$type}} ({{$count}})</li>
            @endif
        @endforeach
        @include('common.show-more-control', [
            'items'    => $fileDescriptionTypes,
            'attrName' => 'hidden-file-type',
            'msg'      => 'file types...'
        ])
    </ul>
</div>