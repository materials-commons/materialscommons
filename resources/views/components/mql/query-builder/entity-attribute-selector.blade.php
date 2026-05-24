<div>
    <div class="row mb-2">
        @if ($category == "computational")
            {{--                    <span class="me-2 ms-3">Having Computation Attribute:</span>--}}
            <span class="me-2 ms-3">View Computation Attribute:</span>
        @else
            {{--                    <span class="me-2 ms-3">Having Sample Attribute:</span>--}}
            <span class="me-2 ms-3">View Sample Attribute:</span>
        @endif
    </div>
    <select id="entity-attributes">
        <option value=""></option>
        @foreach($entityAttributes as $attr)
            <option value="{{$attr->name}}">{{$attr->name}}</option>
        @endforeach
    </select>
    {{--        <div class="row mt-2">--}}
    {{--            <a href="#" class="btn btn-info btn-sm ms-3"><i class="fa fas fa-equals me-2"></i>Where Value Is</a>--}}
    {{--        </div>--}}
    {{--        <hr/>--}}
    {{--        <div class="row mt-2">--}}
    {{--            <a href="#" class="btn btn-success btn-sm ms-3"><i class="fa fas fa-plus me-2"></i>Add Attribute</a>--}}
    {{--        </div>--}}
    <div id="entity-attribute-overview" class="mt-2"></div>

    @push('scripts')
        <script>
            $(document).ready(() => {
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

                setupHavingEntityAttribute();
            });
        </script>
    @endpush
</div>