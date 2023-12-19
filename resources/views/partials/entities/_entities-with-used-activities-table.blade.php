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
@if($category == "computational")
    <h4>Show Computations</h4>
@else
    <h4>Show Samples</h4>
@endif
<div class="row">
    @if($category == "computational")
        <span class="mr-2"><i class="fa fas fa-filter ml-2 mr-2"></i>In Activity:</span>
    @else
        <span class="mr-2"><i class="fa fas fa-filter ml-2 mr-2"></i>In Process:</span>
    @endif
    <select id="activities">
        <option value=""></option>
        @foreach($activities as $activity)
            <option value="{{$activity->name}}">{{$activity->name}}</option>
        @endforeach
    </select>
    <div id="filter">
    </div>
    @if ($category == "computational")
        <span class="mr-2 ml-3">Having Activity Attribute:</span>
    @else
        <span class="mr-2 ml-3">Having Process Attribute:</span>
    @endif
    <select id="activity-attributes">
        @foreach($processAttributes as $attr)
            <option value="{{$attr->name}}">{{$attr->name}}</option>
        @endforeach
    </select>

    @if ($category == "computational")
        <span class="mr-2 ml-3">Having Computation Attribute:</span>
    @else
        <span class="mr-2 ml-3">Having Sample Attribute:</span>
    @endif
    <select id="entity-attributes">
        @foreach($sampleAttributes as $attr)
            <option value="{{$attr->name}}">{{$attr->name}}</option>
        @endforeach
    </select>
</div>
<br/>
<br/>
<table id="entities-with-used-activities" class="table table-hover mt-4" style="width: 100%">
    <thead>
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
            $('#entities-with-used-activities').DataTable({
                pageLength: 100,
                scrollX: true,
                initComplete: function () {
                    let api = this.api();
                    $('#activities').on('change', function () {
                        let columns = api.columns().flatten();
                        let selected = $(this).val();
                        if (selected === '') {
                            api.search('').columns().search('').draw();
                            return;
                        }
                        for (let i = 0; i < columns.length; i++) {
                            let colHeader = api.column(i).header().textContent;
                            if (selected === colHeader) {
                                api.search('').columns().search('').draw();
                                api.column(i).search(`^X$`, true, false).draw();
                                break;
                            }
                        }
                    });
                    // select.append($(`<option value=""></option>`));
                    // api.columns().every(function (idx) {
                    //     if (idx === 0) {
                    //         return;
                    //     }
                    //
                    //     if (hasExperiment && idx === 1) {
                    //         return;
                    //     }
                    //     let column = this;
                    //     let headerText = column.header().textContent;
                    //     select.append($(`<option value="${headerText}">${headerText}</option>`));
                    // });
                },
            });

            setupHavingActivityAttribute();
            setupHavingEntityAttribute();
        });
    </script>
@endpush
