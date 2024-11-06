<div x-data="datahqCreateChartModal">
    <x-modal :id="$modalId" :title="'Create New Chart'">
        <x-slot:body>
            <div class="mt-2 ml-4" id="chart-controls">
                <div class="row">
                    <div class="form-group">
                        <input type="text" class="form-control" id="chart-name"
                               @keyup.enter.prevent="handleCreateViewForChart()" placeholder="Enter chart name..."/>
                    </div>
                </div>
            </div>
        </x-slot:body>
        <x-slot:footer>
            <a class="btn btn-success" @click.prevent="handleCreateViewForChart()">Create View</a>
        </x-slot:footer>

        @push('scripts')
            <script>
                mcutil.onAlpineInit("datahqCreateChartModal", () => {
                    return {
                        handleCreateViewForChart() {
                            let tab = "{{$tab}}";
                            let stateService = "{{$stateService}}";
                            let projectId = "{{$project->id}}";

                            let chartName = $("#chart-name").val();
                            let formData = new FormData();

                            formData.append('tab', tab);
                            formData.append('chart_name', chartName);

                            let config = {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            };

                            switch (stateService) {
                                case 'sampleshq':
                                    let r = route('projects.datahq.sampleshq.create-chart-subview', {
                                        project: projectId,
                                    });
                                    axios.post(r, formData, config).then((resp) => {
                                        let subview = resp.data;
                                        window.location.href = route('projects.datahq.sampleshq.index', {
                                            project: projectId,
                                            tab: tab,
                                            subview: subview
                                        });
                                    });
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                });
            </script>
        @endpush
    </x-modal>
</div>