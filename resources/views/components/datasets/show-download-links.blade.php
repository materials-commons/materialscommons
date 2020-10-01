@if(file_exists($dataset->zipfilePath()) || file_exists($dataset->publishedGlobusPath()))
    <ul class="list-inline mt-2" style="margin-bottom: 3px">
        <li class="list-inline-item">Download:</li>
        @if(file_exists($dataset->zipfilePath()))
            <li class="list-inline-item">
                <a href="{{route('public.datasets.download_zipfile', [$dataset])}}">
                    Zipfile
                </a>
                <span class="ml-1">({{formatBytes($dataset->zipfileSize())}})</span>
            </li>
        @endif

        @if(file_exists($dataset->publishedGlobusPath()))
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