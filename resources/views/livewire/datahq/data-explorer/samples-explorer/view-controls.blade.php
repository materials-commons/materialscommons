<div class="col-12">
    @if($showFilters)
        <x-datahq.mql-controls :project="$project"/>
    @else
        <div class="form-group" x-datax="datahqViewControls">
            <label class="ml-4">Show:</label>
            <div class="btn-group" role="group">
                <a class="action-link ml-3 cursor-pointer" wire:click.prevent="toggleProcesses()">
                    <i class="fa fas fa-code-branch mr-2"></i>Processes
                </a>
                <a class="action-link ml-4 cursor-pointer" wire:click.prevent="toggleSampleAttributes()">
                    <i class="fa fas fa-cubes mr-2"></i>Sample Attributes
                </a>
                <a class="action-link ml-4 cursor-pointer" wire:click.prevent="toggleProcessAttributes()">
                    <i class="fa fas fa-project-diagram mr-2"></i>Process Attributes
                </a>
            </div>
        </div>
        <div class="row">
            @if($showProcessesTable)
                <div class="col-12 border rounded">
                    <br/>
                    processes table
                    {{--                <x-projects.processes.processes-table :project="$project"/>--}}
                    <br/>
                </div>
            @endif

            @if($showSampleAttributesTable)
                <div class="col-12 border rounded">
                    <br/>
                    <livewire:projects.entities.entity-attributes-table :project="$project"
                                                                        :experiment="$experiment"
                                                                        :category="'experimental'"/>
                    <br/>
                </div>
            @endif

            @if($showProcessAttributesTable)
                <div class="col-12 border rounded">
                    <br/>
                    <livewire:projects.activities.activity-attributes-table :project="$project"
                                                                            :experiment="$experiment"/>
                    <br/>
                </div>
            @endif
        </div>
        <hr/>
    @endif

    {{--    <a class="action-link cursor-pointer float-right ml-4">--}}
    {{--        <i class="fa fas fa-table mr-2"></i> New Table--}}
    {{--    </a>--}}

    <a class="action-link cursor-pointer float-right" wire:click.prevent="$parent.addChart()">
        <i class="fa fas fa-chart-area mr-2"></i> New Chart
    </a>
</div>
