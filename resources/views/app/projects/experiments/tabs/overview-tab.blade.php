<x-card-container>
    <x-show-standard-details :item="$experiment"/>
    @if(!is_null($experiment->sheet))
        <div class="mb-3">
            <span class="fs-10 grey-5">Loaded from Google Sheet:
                <a href="{{$experiment->sheet->url}}" target="_blank" class="no-underline">
                    {{$experiment->sheet->title}}
                </a>
            </span>
        </div>
    @elseif (!is_null($experiment->loaded_file_path))
        <div class="mb-3">
    <span class="fs-10 grey-5">Loaded from file: <a
            href="{{route('projects.files.by-path', [$project, 'path' => $experiment->loaded_file_path])}}"
            class="no-underline">{{$experiment->loaded_file_path}}</a></span>
        </div>
    @endif
    @include('partials.overview._overview')
</x-card-container>
