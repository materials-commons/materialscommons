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
                <h3>Show samples explorer</h3>
            @elseif($view == "computations-explorer")
                <h3>Show computations explorer</h3>
            @elseif($view == "processes-explorer")
                <h3>show processes explorer</h3>
            @endif
        </x-slot:body>
    </x-card>
</div>
