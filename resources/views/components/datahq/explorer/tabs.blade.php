<div>
    <a class="action-link float-right"
       href="{{route('projects.datahq.add-filtered-view', [$project, 'state-service' => 'sampleshq'])}}">
        <i class="fa fas fa-plus mr-2"></i> Add Filtered View
    </a>
    <ul class="nav nav-tabs col-12">
        @foreach($tabs as $tab)
            <li class="nav-item">
                <a class="nav-link no-underline {{setActiveNavByTabParam($tab->key)}}"
                   href="{{route('projects.datahq.sampleshq.index', [$project, 'tab' => $tab->key])}}">
                    {{$tab->name}}
                </a>
            </li>
        @endforeach
    </ul>
</div>