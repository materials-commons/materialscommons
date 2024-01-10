@if(Request::routeIs('projects.entities.*'))
    <div id="mql-query-builder" x-data="initMQLBuilder()">
        <div id="open-query-builder" x-show="!showBuilder">
            <a href="#" @click="toggleShowBuilder()">Open Query Builder</a>
            <p>
                Query for matching samples by process type and attributes.
            </p>
        </div>
        <div id="query-builder" style="display: none" x-show="showBuilder">
            @include('partials.mql._query-builder')
        </div>
    </div>
    <br>

    <div class="row mb-3">
        @if($category == "computational")
            <h4>Query Computations</h4>
        @else
            <h4>Query Samples</h4>
        @endif
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-7">
            <div class="row mb-2">
                @if($category == "computational")
                    <span class="mr-2 ml-3">In Activity:</span>
                @else
                    <span class="mr-2 ml-3">In Process:</span>
                @endif
            </div>
            <select id="activities">
                <option value=""></option>
                @foreach($activities as $activity)
                    <option value="{{$activity->name}}">{{$activity->name}}</option>
                @endforeach
            </select>
            {{--        <div class="row mt-2">--}}
            {{--            <a href="#" class="btn btn-success btn-sm ml-3"><i class="fa fas fa-plus mr-2"></i>Add Process</a>--}}
            {{--        </div>--}}
        </div>

        <div class="col-lg-4 col-md-6 col-sm-7">
            <div class="row mb-2">
                @if ($category == "computational")
                    {{--                    <span class="mr-2 ml-3">Having Activity Attribute:</span>--}}
                    <span class="mr-2 ml-3">View Activity Attribute:</span>
                @else
                    {{--                    <span class="mr-2 ml-3">Having Process Attribute:</span>--}}
                    <span class="mr-2 ml-3">View Process Attribute:</span>
                @endif
            </div>
            <select id="activity-attributes">
                <option value=""></option>
                @foreach($processAttributes as $attr)
                    <option value="{{$attr->name}}">{{$attr->name}}</option>
                @endforeach
            </select>
            {{--        <div class="row mt-2">--}}
            {{--            <a href="#" class="btn btn-info btn-sm ml-3"><i class="fa fas fa-equals mr-2"></i>Where Value Is</a>--}}
            {{--        </div>--}}
            {{--        <hr/>--}}
            {{--        <div class="row mt-2">--}}
            {{--            <a href="#" class="btn btn-success btn-sm ml-3"><i class="fa fas fa-plus mr-2"></i>Add Attribute</a>--}}
            {{--        </div>--}}
            <div id="activity-attribute-overview" class="mt-2"></div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-7">
            <div class="row mb-2">
                @if ($category == "computational")
                    {{--                    <span class="mr-2 ml-3">Having Computation Attribute:</span>--}}
                    <span class="mr-2 ml-3">View Computation Attribute:</span>
                @else
                    {{--                    <span class="mr-2 ml-3">Having Sample Attribute:</span>--}}
                    <span class="mr-2 ml-3">View Sample Attribute:</span>
                @endif
            </div>
            <select id="entity-attributes">
                <option value=""></option>
                @foreach($sampleAttributes as $attr)
                    <option value="{{$attr->name}}">{{$attr->name}}</option>
                @endforeach
            </select>
            {{--        <div class="row mt-2">--}}
            {{--            <a href="#" class="btn btn-info btn-sm ml-3"><i class="fa fas fa-equals mr-2"></i>Where Value Is</a>--}}
            {{--        </div>--}}
            {{--        <hr/>--}}
            {{--        <div class="row mt-2">--}}
            {{--            <a href="#" class="btn btn-success btn-sm ml-3"><i class="fa fas fa-plus mr-2"></i>Add Attribute</a>--}}
            {{--        </div>--}}
            <div id="entity-attribute-overview" class="mt-2"></div>
        </div>
    </div>
    <div id="attr-modal-here" class="modal fade" stylex="display:none" tabindex="-1" aria-hidden="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
    <div class="row mt-4 mb-4">
        <a onclick="toggleAttributesTable()" class="btn btn-info btn-sm ml-3"><i class="fa fas fa-list mr-2"></i>Show/Hide
            All Attributes</a>
    </div>
    <div id="attributes-overview-div" style="display:none">
        <table id="attributes-overview-table" class="table table-hover mt-4" style="width: 100%">
            <thead>
            <th>Attribute</th>
            <th>Type</th>
            <th>Min</th>
            <th>Max</th>
            <th>#Unique Values</th>
            </thead>
            <tbody>
            @foreach($processAttributeDetails as $attr)
                <tr>
                    <td>
                        <a href="#"
                           hx-get="{{route('projects.activities.attributes.show-details-by-name', [$project, $attr->name, 'modal' => 'true'])}}"
                           hx-target="#attr-modal-here"
                           data-bs-toggle="modal"
                           data-bs-target="#attr-modal-here">{{$attr->name}}</a>
                    </td>
                    <td>Process</td>
                    <td>
                        @if($attr->min != 0 && $attr->max != 0)
                            {{$attr->min}}
                        @endif
                    </td>
                    <td>
                        @if($attr->min != 0 && $attr->max != 0)
                            {{$attr->max}}
                        @endif
                    </td>
                    <td>{{$attr->count}}</td>
                </tr>
            @endforeach
            @foreach($sampleAttributeDetails as $attr)
                <tr>
                    <td>
                        <a href="#"
                           hx-get="{{route('projects.entities.attributes.show-details-by-name', [$project, $attr->name, 'modal' => 'true'])}}"
                           hx-target="#attr-modal-here"
                           data-bs-toggle="modal"
                           data-bs-target="#attr-modal-here">{{$attr->name}}</a>
                    </td>
                    <td>Sample</td>
                    <td>
                        @if($attr->min != 0 && $attr->max != 0)
                            {{$attr->min}}
                        @endif
                    </td>
                    <td>
                        @if($attr->min != 0 && $attr->max != 0)
                            {{$attr->max}}
                        @endif
                    </td>
                    <td>{{$attr->count}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <br/>
    <br/>
@endif
<table id="entities-with-used-activities" class="table table-hover mt-4" style="width: 100%">
    <thead>
    <th>Name1</th>
    <th>Name</th>
    @if(isset($showExperiment))
        <th>Experiment</th>
    @endif
    @foreach($activities as $activity)
        <th>{{$activity->name}}</th>
    @endforeach
    </thead>
    <tbody>
    @foreach($entities as $entity)
        <tr>
            <td>{{$entity->name}}</td>
            <td>
                @if(isset($experiment))
                    <a href="{{route('projects.experiments.entities.by-name.spread', [$project, $experiment, "name" => urlencode($entity->name)])}}">
                        {{$entity->name}}
                    </a>
                @else
                    <a href="{{route('projects.entities.show-spread', [$project, $entity])}}">
                        {{$entity->name}}
                    </a>
                @endif
            </td>
            @if(isset($showExperiment))
                <td>
                    @if(isset($entity->experiments))
                        @if($entity->experiments->count() > 0)
                            {{$entity->experiments[0]->name}}
                        @endif
                    @endif
                </td>
            @endif
            @if(isset($usedActivities[$entity->id]))
                @foreach($usedActivities[$entity->id] as $used)
                    @if($used)
                        <td>X</td>
                        {{--                    <td>{{$entity->name}}</td>--}}
                        {{--                    <td>{{$entity->name}} ({{$used}})</td>--}}
                    @else
                        <td></td>
                    @endif
                @endforeach
            @endif
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        let hasExperiment = false;
        @if($showExperiment)
            hasExperiment = true;
        @endif

        let findMatchingRoute = "{{route('api.queries.find-matching-entities', [$project])}}";
        let apiToken = "{{auth()->user()->api_token}}";

        let projectId = "{{$project->id}}";

        function setupInProcessingStep() {

        }

        function setupHavingEntityAttribute() {
            $('#entity-attributes').on('change', function () {
                let value = $(this).val();
                if (value !== "") {
                    let r = route('projects.entities.attributes.show-details-by-name', [projectId, value])
                    htmx.ajax("GET", r, '#entity-attribute-overview');
                } else {
                    let r = route('projects.attributes.close-details-by-name', [projectId, 'xx'])
                    htmx.ajax("GET", r, '#entity-attribute-overview');
                }
            });
        }

        function setupHavingActivityAttribute() {
            $('#activity-attributes').on('change', function () {
                let value = $(this).val();
                if (value !== "") {
                    let r = route('projects.activities.attributes.show-details-by-name', [projectId, value])
                    htmx.ajax("GET", r, '#activity-attribute-overview');
                } else {
                    let r = route('projects.attributes.close-details-by-name', [projectId, 'xx'])
                    htmx.ajax("GET", r, '#activity-attribute-overview');
                }
            });
        }

        function setupHavingProcess() {
            let api = $('#entities-with-used-activities').DataTable();
            $('#activities').on('change', function () {
                let selected = $(this).val();
                if (selected === '') {
                    api.search('').columns().search('').draw();
                    return;
                }
                axios.post(`${findMatchingRoute}`, {
                        activities: [
                            {
                                name: selected,
                                operator: "in"
                            }
                        ]
                    },
                    {
                        headers: {
                            Authorization: `Bearer ${apiToken}`
                        }
                    }
                ).then((r) => {
                    if (r.data.entities.length !== 0) {
                        let searchStr = "";
                        for (let i = 0; i < r.data.entities.length; i++) {
                            let e = r.data.entities[i];
                            if (i === 0) {
                                searchStr = e;
                            } else {

                                searchStr = searchStr + `|^${e}$`;
                            }
                        }
                        // api.search('').columns().search('').draw();
                        api.column(0).search(searchStr, true, false).draw();
                    }
                }).catch((e) => {
                    console.log("error: ", e);
                });
            });
        }

        $(document).ready(() => {
            $('#entities-with-used-activities').DataTable({
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

            setupHavingProcess();
            setupHavingActivityAttribute();
            setupHavingEntityAttribute();
        });

        let attributesOverviewShown = false;

        function toggleAttributesTable() {
            if (attributesOverviewShown) {
                document.getElementById("attributes-overview-div").style.display = "none";
                attributesOverviewShown = false;
            } else {
                document.getElementById("attributes-overview-div").style.display = "";
                $('#attributes-overview-table').DataTable().destroy();
                $('#attributes-overview-table').DataTable({
                    pageLength: 100,
                    scrollX: true,
                    fixedHeader: {
                        header: true,
                        headerOffset: 46,
                    },
                });
                attributesOverviewShown = true;
            }
        }

        htmx.on('htmx:after-settle', (evt) => {
            if (evt.target.id === "mql-query") {
                mcutil.autosizeTextareas();
            }
        });

        htmx.on('htmx:afterSwap', (evt) => {
            if (evt.target.id === "attr-modal-here") {
                $('#attr-modal-here').modal('show');
            }
        });

        function initMQLBuilder() {
            return {
                showBuilder: false,
                showSavedQueries: false,
                toggleShowBuilder() {
                    this.showBuilder = !this.showBuilder;
                    if (this.showBuilder) {
                        mcutil.autosizeTextareas();
                    }
                },
                toggleShowSavedQueries() {
                    this.showSavedQueries = !this.showSavedQueries;
                }
            };
        }
    </script>
@endpush
