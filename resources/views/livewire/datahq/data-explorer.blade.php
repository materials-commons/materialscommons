<div>
    <x-card>
        <x-slot:header>
            Data Explorer
            <livewire:datahq.data-explorer.header-controls :project="$project"
                                                           :selected-data="$context"
                                                           :selected-explorer="$explorer"/>
        </x-slot:header>

        <x-slot:body>
            @if($explorer == "overview")
                <livewire:datahq.data-explorer.overview-explorer :project="$project"
                                                                 :experiment="$experiment"
                                                                 :instance="$instance"
                                                                 :context="$context"
                                                                 :view="$view"/>
            @elseif($explorer == "samples")
                <livewire:datahq.data-explorer.samples-explorer :project="$project"/>
            @elseif($explorer == "computations-explorer")
                <livewire:datahq.data-explorer.computations-explorer :project="$project"/>
            @elseif($explorer == "processes-explorer")
                <livewire:datahq.data-explorer.processes-explorer :project="$project"/>
            @endif
        </x-slot:body>
    </x-card>
</div>
