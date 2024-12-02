<div>
    <x-card>
        <x-slot:header>
            Data Explorer
            <livewire:datahq.data-explorer.header-controls :project="$project"/>
        </x-slot:header>

        <x-slot:body>
            <div>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a @class(["nav-link", "no-underline", "active" => $tab == 'samples'])
                           wire:click.prevent="setTab('samples')"
                           href="#">
                            Samples
                        </a>
                    </li>

                    <li class="nav-item">
                        <a @class(["nav-link", "no-underline", "active" => $tab == 'computations'])
                           wire:click.prevent="setTab('computations')"
                           href="#">
                            Computations
                        </a>
                    </li>

                    <li class="nav-item">
                        <a @class(["nav-link", "no-underline", "active" => $tab == 'processes'])
                           wire:click.prevent="setTab('processes')"
                           href="#">
                            Processes
                        </a>
                    </li>

                    <li class="nav-item">
                        <a @class(["nav-link", "no-underline", "active" => $tab == 'sampleattrs'])
                           wire:click.prevent="setTab('sampleattrs')"
                           href="#">
                            Sample Attributes
                        </a>
                    </li>

                    <li class="nav-item">
                        <a @class(["nav-link", "no-underline", "active" => $tab == 'computationattrs'])
                           wire:click.prevent="setTab('computationattrs')"
                           href="#">
                            Computation Attributes
                        </a>
                    </li>

                    <li class="nav-item">
                        <a @class(["nav-link", "no-underline", "active" => $tab == 'processattrs'])
                           wire:click.prevent="setTab('processattrs')"
                           href="#">
                            Process Attributes
                        </a>
                    </li>

                </ul>
                <div class="mt-2">
                    @if($tab == 'samples')
                        <x-projects.samples.samples-table :project="$project"/>
                    @elseif($tab == 'computations')
                        <x-projects.computations.computations-table :project="$project"/>
                    @elseif($tab == 'processes')
                        <x-projects.processes.processes-table :project="$project"/>
                    @elseif($tab == 'sampleattrs')
                        <x-projects.samples.sample-attributes-table :project="$project"/>
                    @elseif($tab == 'computationattrs')
                        <x-projects.computations.computation-attributes-table :project="$project"/>
                    @elseif($tab == 'processattrs')
                        <x-projects.processes.process-attributes-table :project="$project"/>
                    @endif
                </div>
            </div>
        </x-slot:body>
    </x-card>
</div>
