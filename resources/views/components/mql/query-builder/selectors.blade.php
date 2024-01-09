<div>
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
            {{--            <div class="row mt-2">--}}
            {{--                <a href="#" class="btn btn-success btn-sm ml-3"><i class="fa fas fa-plus mr-2"></i>Add Process</a>--}}
            {{--            </div>--}}
        </div>

        <div class="col-lg-4 col-md-6 col-sm-7">
            <div class="row mb-2">
                @if ($category == "computational")
                    {{--                    <span class="mr-2 ml-3">Having Activity Attribute:</span>--}}
                    <span class="mr-2 ml-3">View Activity Attribute:</span>
                @else
                    <span class="mr-2 ml-3">Having Process Attribute:</span>
                    {{--                    <span class="mr-2 ml-3">View Process Attribute:</span>--}}
                @endif
            </div>
            <select id="activity-attributes">
                <option value=""></option>
                @foreach($processAttributes as $attr)
                    <option value="{{$attr->name}}">{{$attr->name}}</option>
                @endforeach
            </select>
            <div class="row mt-2">
                <a href="#" onclick="addWhereValueIsForActivity()" class="btn btn-info btn-sm ml-3"><i
                            class="fa fas fa-equals mr-2"></i>Where Value Is</a>
            </div>
            <div id="activity-where-1"></div>
            <hr/>
            <div class="row mt-2">
                <a href="#" class="btn btn-success btn-sm ml-3"><i class="fa fas fa-plus mr-2"></i>Add Attribute</a>
            </div>
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
    @push('scripts')
        <script>
            function setupHavingEntityAttribute() {
                let projectId = "{{$project->id}}";
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
                let projectId = "{{$project->id}}";
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
                let findMatchingRoute = "{{route('api.queries.find-matching-entities', [$project])}}";
                let apiToken = "{{auth()->user()->api_token}}";
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

            let lastId = 1;

            function addWhereValueIsForActivity() {
                let projectId = "{{$project->id}}";
                let attrName = $('#activity-attributes').val();
                let r = route('projects.query-builder.add-where', [projectId, attrName, 'activity', lastId]);
                htmx.ajax('GET', r, `#activity-where-${lastId}`);
            }

            $(document).ready(() => {
                setupHavingProcess();
                setupHavingActivityAttribute();
                setupHavingEntityAttribute();
            });
        </script>
    @endpush
</div>