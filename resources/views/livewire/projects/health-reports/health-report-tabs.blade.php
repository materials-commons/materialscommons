<div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a @class(["nav-link no-underline", "active" => $tab == 'missing'])
               href="#"
               wire:click.prevent="setTab('missing')">
                @if($healthReport->missingFiles->count() > 0)
                    <span class="text-danger">({{$healthReport->missingFiles->count()}}) Missing Files</span>
                @else
                    Missing Files
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a @class(["nav-link no-underline", "active" => $tab == 'multiple'])
               href="#"
               wire:click.prevent="setTab('multiple')">
                @if($healthReport->multipleCurrentFiles->count() > 0)
                    <span class="text-warning">({{$healthReport->multipleCurrentFiles->count()}}) Multiple Active Versions</span>
                @else
                    Multiple Active Versions
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a @class(["nav-link no-underline", "active" => $tab == 'unpublished'])
               href="#"
               wire:click.prevent="setTab('unpublished')">
                @if($healthReport->unpublishedDatasetsWithDOIs->count() > 0)
                    ({{$healthReport->unpublishedDatasetsWithDOIs->count()}}) Unpublished Datasets with DOIs
                @else
                    Unpublished Datasets with DOIs
                @endif
            </a>
        </li>
    </ul>
    <br/>
    @if($tab == 'missing')
        @if($healthReport->missingFiles->count() > 0)
            <x-projects.health-reports.issues._missing-files :files="$healthReport->missingFiles"/>
        @else
            <p><i class="fas fa-check-circle fa-lg text-green"></i> No missing files</p>
        @endif
    @elseif($tab == 'multiple')
        @if($healthReport->multipleCurrentFiles->count() > 0)
            <x-projects.health-reports.issues._multiple-active-files :files="$healthReport->multipleCurrentFiles"/>
        @else
            <p><i class="fas fa-check-circle fa-lg text-green"></i> No duplicated files in any project directory</p>
        @endif
    @elseif($tab == 'unpublished')
        @if($healthReport->unpublishedDatasetsWithDOIs->count() > 0)
            <x-projects.health-reports.issues._unpublished-datasets :datasets="$healthReport->unpublishedDatasetsWithDOIs"/>
        @else
            <p><i class="fas fa-check-circle fa-lg text-green"></i> No unpublished datasets that have a DOI assigned</p>
        @endif
    @endif
</div>
