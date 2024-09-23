<div>
    @if($tab == 'all-samples')
        <x-datahq.view-controls :project="$project" :show-filters="false"/>
        <x-projects.samples.samples-table :project="$project"/>
    @else
        <x-datahq.view-controls :project="$project" :show-filters="true"/>
        <x-projects.samples.samples-table :project="$project"/>
    @endif
            {{--        <div id="view-here">Appending Here</div>--}}
</div>