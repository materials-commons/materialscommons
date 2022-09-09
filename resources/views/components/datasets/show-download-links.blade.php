@if($dataset->zipfile_size > 0 || $dataset->globus_path_exists)
    <ul class="list-inline mt-2" style="margin-bottom: 3px">
        <li class="list-inline-item">Download:</li>
        @if($dataset->zipfile_size > 0)
            <li class="list-inline-item">
                @if(is_null($dataset->published_at))
                    <a href="{{route('projects.datasets.download_zipfile', [$dataset->project_id, $dataset])}}">
                        Zipfile
                    </a>
                @else
                    <a href="{{route('public.datasets.download_zipfile', [$dataset])}}">
                        Zipfile
                    </a>
                @endif
                <span class="ml-1">({{formatBytes($dataset->zipfile_size)}})</span>
            </li>
        @endif

        @if($dataset->globus_path_exists)
            <li class="list-inline-item">
                <a href="{{route('public.datasets.download_globus', [$dataset])}}" class="ml-2"
                   target="_blank">
                    Using Globus
                </a>
            </li>
        @endif
    </ul>
@else
    <div class="mt-2"></div>
@endif
