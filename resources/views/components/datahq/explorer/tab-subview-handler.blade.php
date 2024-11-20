<div>
    @if($subview == "index")
        <x-projects.samples.samples-table :project="$project"/>
    @elseif($subviewState->viewType === 'chart')
        <livewire:datahq.charts.show-subview-chart :project="$project"
                                                   :subview="$subview"
                                                   :subview-state="$subviewState"/>
    @elseif($subviewState->viewType === 'table')
        <h4>Tab Subview Handler for type {{$subviewState->viewType}}</h4>
    @else
        <h4>Unknown view type {{$subview->viewType}}</h4>
    @endif
</div>
