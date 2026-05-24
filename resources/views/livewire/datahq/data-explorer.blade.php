<div>
    <h3 class="text-center">Data Explorer</h3>
    <br/>
    <livewire:datahq.data-explorer.header-controls :project="$project"
                                                   :selected-data="$context"
                                                   :selected-explorer="$explorer"/>
    <br/>

    @if($explorer == "overview")
        <span>Overview</span>
        <livewire:datahq.data-explorer.overview-explorer :key="$key"
                                                         :project="$project"
                                                         :experiment="$experiment"
                                                         :context="$context"
                                                         :view="$view"/>
    @elseif($explorer == "samples")
        <livewire:datahq.data-explorer.samples-explorer :key="$key"
                                                        :project="$project"
                                                        :experiment="$experiment"
                                                        :context="$context"
                                                        :view="$view"
                                                        :subview="$subview"
                                                        :instance="$instance"/>
    @elseif($explorer == "computations")
        <span>Computations</span>
        <livewire:datahq.data-explorer.computations-explorer :key="$key"
                                                             :project="$project"
                                                             :experiment="$experiment"/>
    @elseif($explorer == "processes")
        <span>Processes</span>
        <livewire:datahq.data-explorer.processes-explorer :key="$key"
                                                          :project="$project"
                                                          :experiment="$experiment"/>
    @endif
</div>
