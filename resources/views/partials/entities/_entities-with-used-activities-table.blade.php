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
@endif
<div class="row">
    @if($category == "computational")
        <span class="mr-2"><i class="fa fas fa-filter ml-2 mr-2"></i>Show Computations In:</span>
    @else
        <span class="mr-2"><i class="fa fas fa-filter ml-2 mr-2"></i>Show Samples In:</span>
    @endif
    <div id="filter">
    </div>
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
        @if(isset($showExperiment))
            hasExperiment = true;
        @endif
        $(document).ready(() => {
            $('#entities-with-used-activities').DataTable({
                pageLength: 100,
                scrollX: true,
                initComplete: function () {
                    let api = this.api();
                    let select = $('<select/>').appendTo('#filter').on('change', function () {
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
                    select.append($(`<option value=""></option>`));
                    this.api().columns().every(function (idx) {
                        if (idx === 0) {
                            return;
                        }

                        if (hasExperiment && idx === 1) {
                            return;
                        }
                        let column = this;
                        let headerText = column.header().textContent;
                        select.append($(`<option value="${headerText}">${headerText}</option>`));
                    });
                },
            });

        });
        htmx.on('htmx:after-settle', (evt) => {
            if (evt.target.id === "mql-query") {
                mcutil.autosizeTextareas();
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
