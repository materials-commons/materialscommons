<span class="fs-10 grey-5">Path: {{$file->fullPath()}}</span>
<a href="#" onclick="mcutil.copyToClipboard('{{$file->fullPath()}}')" class="ml-2"><i class="fa fas fa-clone"></i></a>
@if($fileExists($file))
    @switch($fileType($file))
        @case("text")
            @if(isset($displayRoute))
                <a class="ml-4" href="{{$displayRoute}}">Fullscreen</a>
            @endif
            @if($file->size <= 2000000)
                <a href="#" onclick="mcutil.copyToClipboard('#file-contents')" class="ml-4">
                    copy file contents
                </a>
            @endif
            @if(isset($editRoute))
                <a href="{{$editRoute}}" class="ml-4">Edit</a>
            @endif
            @break

        @case("markdown")
            @if(isset($displayRoute))
                <a href="{{$displayRoute}}">Fullscreen</a>
            @endif
            @if($file->size <= 2000000)
                <a href="#" onclick="mcutil.copyToClipboard('#file-contents')" class="ml-4">
                    copy file contents
                </a>
            @endif
            @if(isset($editRoute))
                <a href="{{$editRoute}}" class="ml-4">Edit</a>
            @endif
            @break
    @endswitch
@endif
<x-show-standard-details :item="$file">
    <span class="ml-3 fs-10 grey-5">Mediatype: {{$file->mime_type}}</span>
    <span class="ml-3 fs-10 grey-5">Size: {{$file->toHumanBytes()}}</span>
</x-show-standard-details>