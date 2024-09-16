<div>
    <a class="action-link float-right">
        <i class="fa fas fa-plus mr-2"></i> Add Filtered View
    </a>
    <ul class="nav nav-tabs col-12">
        <li class="nav-item">
            <a class="nav-link no-underline {{setActiveNavByTabParam('all-samples')}}"
               href="{{route('projects.datahq.sampleshq.index', [$project, 'tab' => 'all-samples'])}}">
                All Samples
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link no-underline {{setActiveNavByTabParam('fv1')}}"
               href="{{route('projects.datahq.sampleshq.index', [$project, 'tab' => 'fv1'])}}">
                Filtered View 1
            </a>
        </li>
    </ul>

    <br/>

    <x-datahq.view-controls :project="$project" :show-filters="false"/>

    <x-projects.samples.samples-table :project="$project"/>
</div>
