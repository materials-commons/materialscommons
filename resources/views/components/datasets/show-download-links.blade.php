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
                    @auth
                        <a href="{{route('public.datasets.download_zipfile', [$dataset])}}">
                            Zipfile
                        </a>
                    @else
                        <a data-toggle="modal" href="#ds-download-zip">Zipfile</a>
                    @endauth
                @endif
                <span class="ml-1">({{formatBytes($dataset->zipfile_size)}})</span>
            </li>
        @else
            @if(!is_null($dataset->published_at))
                <li class="list-inline-item">
                    <span class="ml-1">Building zipfile...</span>
                </li>
            @endif
        @endif

        @if($dataset->globus_path_exists)
            <li class="list-inline-item">
                @auth
                    <a href="{{route('public.datasets.download_globus', [$dataset])}}" class="ml-2"
                       target="_blank">
                        Using Globus
                    </a>
                @else
                    <a class="ml-2" data-toggle="modal" href="#ds-download-globus">Using Globus</a>
                @endauth
            </li>
        @endif
    </ul>
@else
    <div class="mt-2"></div>
@endif
