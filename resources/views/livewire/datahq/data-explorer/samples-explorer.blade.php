<div>
    <a class="action-link float-right" href="#" wire:click.prevent="addFilteredView">
        <i class="fa fas fa-plus mr-2"></i> Add Filtered View
    </a>
    <ul class="nav nav-pills col-12">
        @foreach($instance->samples_explorer_state->views as $view)
            <li wire:key="{{$view->name}}" class="nav-item">
                <a wire:click.prevent="setView('{{$view->name}}')"
                   @class(["nav-link", "no-underline", "active" => $view->name == $instance->samples_explorer_state->currentView])
                   href="#">
                    {{$view->name}}
                </a>
            </li>
        @endforeach
    </ul>

    <br/>
    <livewire:datahq.data-explorer.samples-explorer.view-controls :instance="$instance"
                                                                  :project="$project"
                                                                  :experiment="$experiment"/>
    <br/>

    <nav class="nav nav-pills mb-3">
        @foreach($currentView->subviews as $subview)
            <a wire:key="{{$subview->name}}-{{$currentView->name}}"
               wire:click.prevent="setSubview('{{$subview->name}}')"
               @class(["nav-link", "no-underline", "rounded-pill", "active" => $subview->name == $view->currentSubview])
               href="#">{{$subview->name}}</a>
        @endforeach
    </nav>

    @if($currentSubview->name == "Samples")
        Showing Samples Table
    @elseif (!is_null($currentSubview->chart))
        <livewire:datahq.data-explorer.display-chart wire:key="{{$dataKey}}"
                                                     :experiment="$experiment"
                                                     :project="$project"
                                                     :chart="$currentSubview->chart"/>
    @elseif(!is_null($currentSubview->table))
        Showing Table
    @else
        Subview is not Samples, a Chart or a Table.
    @endif
</div>
