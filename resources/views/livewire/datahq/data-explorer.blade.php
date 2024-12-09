<div>
    <x-card>
        <x-slot:header>
            Data Explorer
            <livewire:datahq.data-explorer.header-controls :project="$project"
                                                           :selected-data="$context"
                                                           :selected-view="$view"/>
        </x-slot:header>

        <x-slot:body>
            @if($view == "overview")
                <livewire:datahq.data-explorer.overview-explorer :project="$project"
                                                                 :instance="$instance"
                                                                 :context="$context"
                                                                 :tab="$tab"/>
            @elseif($view == "samples-explorer")
                <livewire:datahq.data-explorer.samples-explorer :project="$project"/>
            @elseif($view == "computations-explorer")
                <livewire:datahq.data-explorer.computations-explorer :project="$project"/>
            @elseif($view == "processes-explorer")
                <livewire:datahq.data-explorer.processes-explorer :project="$project"/>
            @endif
        </x-slot:body>
    </x-card>
</div>
