{{--@if(Request::routeIs('projects.entities.*'))--}}
{{--    <div id="mql-query-builder" x-data="initMQLBuilder()">--}}
{{--        <div id="open-query-builder" x-show="!showBuilder">--}}
{{--            <a href="#" @click="toggleShowBuilder()">Open Query Builder</a>--}}
{{--            <p>--}}
{{--                Query for matching samples by process type and attributes.--}}
{{--            </p>--}}
{{--        </div>--}}
{{--        <div id="query-builder" style="display: none" x-show="showBuilder">--}}
{{--            @include('partials.mql._query-builder')--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <br>--}}
{{--@endif--}}
{{--<x-mql.query-builder/>--}}
<div class="row mb-3">
    @if($category == "computational")
        <h4>Query Computations</h4>
    @else
        <h4>Query Samples</h4>
    @endif
    <a href="#" class="btn btn-primary btn-sm ml-3"><i class="fa fas fa-play mr-2"></i>Run Query</a>
    <a href="#" class="btn btn-info btn-sm ml-3"><i class="fa fas fa-list mr-2"></i>Show Attributes Overview</a>
</div>
<div class="row">
    <div class="col-3">
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
        <div class="row mt-2">
            <a href="#" class="btn btn-success btn-sm ml-3"><i class="fa fas fa-plus mr-2"></i>Add Process</a>
        </div>
    </div>

    <div class="col-3">
        <div class="row mb-2">
            @if ($category == "computational")
                <span class="mr-2 ml-3">Having Activity Attribute:</span>
            @else
                <span class="mr-2 ml-3">Having Process Attribute:</span>
            @endif
        </div>
        <select id="activity-attributes">
            @foreach($processAttributes as $attr)
                <option value="{{$attr->name}}">{{$attr->name}}</option>
            @endforeach
        </select>
        <div class="row mt-2">
            <a href="#" class="btn btn-info btn-sm ml-3"><i class="fa fas fa-equals mr-2"></i>Where Value Is</a>
        </div>
        <hr/>
        <div class="row mt-2">
            <a href="#" class="btn btn-success btn-sm ml-3"><i class="fa fas fa-plus mr-2"></i>Add Attribute</a>
        </div>
    </div>

    <div class="col-3">
        <div class="row mb-2">
            @if ($category == "computational")
                <span class="mr-2 ml-3">Having Computation Attribute:</span>
            @else
                <span class="mr-2 ml-3">Having Sample Attribute:</span>
            @endif
        </div>
        <select id="entity-attributes">
            @foreach($sampleAttributes as $attr)
                <option value="{{$attr->name}}">{{$attr->name}}</option>
            @endforeach
        </select>
        <div class="row mt-2">
            <a href="#" class="btn btn-info btn-sm ml-3"><i class="fa fas fa-equals mr-2"></i>Where Value Is</a>
        </div>
        <hr/>
        <div class="row mt-2">
            <a href="#" class="btn btn-success btn-sm ml-3"><i class="fa fas fa-plus mr-2"></i>Add Attribute</a>
        </div>
    </div>
</div>
<br/>
<br/>
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

        function setupInProcessingStep() {

        }

        function setupHavingEntityAttribute() {
            let select = $('#entity-attributes').on('change', function () {
                let value = $(this).val();
                console.log(`entity attribute chosen ${value}`);
            });
        }

        function setupHavingActivityAttribute() {
            let select = $('#activity-attributes').on('change', function () {
                let value = $(this).val();
                console.log(`activity attribute chosen ${value}`);
            });
        }

        $(document).ready(() => {
            let t = $('#entities-with-used-activities').DataTable({
                pageLength: 100,
                scrollX: true,
                fixedHeader: {
                    header: true,
                    headerOffset: 46,
                },
                columnDefs: [
                    {targets: [0], visible: false},
                ],
                initComplete: function () {
                    let api = this.api();
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

                                        searchStr = searchStr + `|${e}`;
                                    }
                                }
                                api.search('').columns().search('').draw();
                                api.column(0).search(searchStr, true, false).draw();
                            }
                        }).catch((e) => {
                            console.log("error: ", e);
                        });
                    });
                },
            });

            setupHavingActivityAttribute();
            setupHavingEntityAttribute();
        });
    </script>
@endpush
