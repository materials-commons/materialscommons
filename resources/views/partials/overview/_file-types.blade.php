{{--<div class="mt-2">--}}
{{--    <h5>File Types ({{sizeof($fileDescriptionTypes)}}):</h5>--}}
{{--    <ul>--}}
{{--        @foreach($fileDescriptionTypes as $type => $count)--}}
{{--            @if($loop->iteration < 12)--}}
{{--                <li>{{$type}} ({{$count}})</li>--}}
{{--            @else--}}
{{--                <li class="hidden-file-type" hidden>{{$type}} ({{$count}})</li>--}}
{{--            @endif--}}
{{--        @endforeach--}}
{{--        @include('common.show-more-control', [--}}
{{--            'items'    => $fileDescriptionTypes,--}}
{{--            'attrName' => 'hidden-file-type',--}}
{{--            'msg'      => 'file types...'--}}
{{--        ])--}}
{{--    </ul>--}}
{{--</div>--}}
@if(isset($fileDescriptionTypes))
    <div class="form-group">
        <label for="file-types">File Types ({{sizeof($fileDescriptionTypes)}})</label>
        <ul class="list-inline">
            @foreach($fileDescriptionTypes as $type => $count)
                <li class="list-inline-item mt-1">
                    <span class="badgex badge-lightx fs-9 grey-5">{{$type}} ({{$count}})</span>
                </li>
            @endforeach
        </ul>
    </div>
@endif