<div>
    <div class="row mb-2">
        @if ($category == "computational")
            {{--                    <span class="mr-2 ml-3">Having Activity Attribute:</span>--}}
            <span class="mr-2 ml-3">View Activity Attribute:</span>
        @else
            {{--            <span class="mr-2 ml-3">Having Process Attribute:</span>--}}
            <span class="mr-2 ml-3">View Process Attribute:</span>
        @endif
    </div>
    <select id="activity-attributes">
        <option value=""></option>
        @foreach($processAttributes as $attr)
            <option value="{{$attr->name}}">{{$attr->name}}</option>
        @endforeach
    </select>
    {{--    <div class="row mt-2">--}}
    {{--        <a href="#" onclick="addWhereValueIsForActivity()" class="btn btn-info btn-sm ml-3"><i--}}
    {{--                    class="fa fas fa-equals mr-2"></i>Where Value Is</a>--}}
    {{--    </div>--}}
    {{--    <div id="activity-where-1"></div>--}}
    {{--    <hr/>--}}
    {{--    <div class="row mt-2">--}}
    {{--        <a href="#" class="btn btn-success btn-sm ml-3"><i class="fa fas fa-plus mr-2"></i>Add Attribute</a>--}}
    {{--    </div>--}}
    <div id="activity-attribute-overview" class="mt-2"></div>
    @push('scripts')
        <script>


            {{--let lastId = 1;--}}

            {{--function addWhereValueIsForActivity() {--}}
            {{--    let projectId = "{{$project->id}}";--}}
            {{--    let attrName = $('#activity-attributes').val();--}}
            {{--    let r = route('projects.query-builder.add-where', [projectId, attrName, 'activity', lastId]);--}}
            {{--    htmx.ajax('GET', r, `#activity-where-${lastId}`);--}}
            {{--}--}}

            $(document).ready(() => {
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

                setupHavingActivityAttribute();
            });
        </script>
    @endpush
</div>