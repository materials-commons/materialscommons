<div>
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

    {{--    <div class="row mb-3">--}}
    {{--        @if($category == "computational")--}}
    {{--            <h4>Query Computations</h4>--}}
    {{--        @else--}}
    {{--            <h4>Query Samples</h4>--}}
    {{--        @endif--}}
    {{--    </div>--}}

    <x-mql.query-builder.selectors :category="$category" :project="$project" :activities="$activities"
                                   :process-attributes="$processAttributes" :sample-attributes="$sampleAttributes"/>

    <div id="attr-modal-here" class="modal fade" tabindex="-1" aria-hidden="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
    <div class="row mt-2 mb-4">
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
    {{--    <br/>--}}
    @push('scripts')
        <script>
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

            htmx.on('htmx:afterSwap', (evt) => {
                if (evt.target.id === "attr-modal-here") {
                    $('#attr-modal-here').modal('show');
                }
            });
        </script>
    @endpush
</div>

