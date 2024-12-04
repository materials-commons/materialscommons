<div>
    <x-card>
        <x-slot:header>
            Data Explorer
            <livewire:datahq.data-explorer.header-controls :project="$project"/>
        </x-slot:header>

        <x-slot:body>
            @if($view == "overview")
                <livewire:datahq.data-explorer.overview-explorer :project="$project" :instance="$instance" :tab="$tab"/>
            @elseif($view == "samples-explorer")
                <h3>Show samples explorer</h3>
            @elseif($view == "computations-explorer")
                <h3>Show computations explorer</h3>
            @elseif($view == "processes-explorer")
                <h3>show processes explorer</h3>
            @endif
        </x-slot:body>
    </x-card>
    @push('scripts')
        <script>
            // This is a workaround until I have a better solution. I'm putting all the alpine component
            // initialization code. It will remain in the components until I refactor them.

            mcutil.onAlpineInit("samplesTable", () => {
                return {
                    dt: null,
                    init() {
                        this.dt = mcutil.initDataTable('#entities-with-used-activities', {
                            pageLength: 100,
                            scrollX: true,
                            fixedHeader: {
                                header: true,
                                headerOffset: 46,
                            },
                            columnDefs: [
                                {targets: [0], visible: false},
                            ],
                        });
                    }
                }
            });

            mcutil.onAlpineInit("processAttributesTable", () => {
                return {
                    dt: null,
                    init() {
                        this.dt = mcutil.initDataTable("#activities-dd", {
                            pageLength: 100,
                            stateSave: true
                        });
                    }
                }
            });
        </script>
    @endpush
</div>
