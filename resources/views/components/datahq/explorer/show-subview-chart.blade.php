<div>
    @switch($subviewState->viewData->dataType)
        @case('line')
            <x-charts.show-line-chart/>
            @break
        @case('scatter')
            <x-charts.show-scatter-chart/>
            @break
        @case('histogram')
            <x-charts.show-histogram/>
            @break
        @default
            <span>Chart type {{$subviewState->viewData->dataType}} not implemented</span>
            @break
    @endswitch
</div>