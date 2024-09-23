<div>
    <div class="mt-2" id="table-controls">
        <div class="row">
            <div class="form-group">
                <label>Sample Attributes:</label>
                <select name="" class="selectpicker" data-style="btn-light no-tt" id="table-sample-attributes"
                        data-live-search="true" data-actions-box="true" multiple>
                    @foreach($sampleAttributes as $attr)
                        <option value="{{$attr->name}}">{{$attr->name}}</option>
                    @endforeach
                </select>

                <label class="ml-4">Process Attributes:</label>
                <select name="" class="selectpicker" data-style="btn-light no-tt" id="table-process-attributes"
                        data-live-search="true" data-actions-box="true" multiple>
                    @foreach($processAttributes as $attr)
                        <option value="{{$attr->name}}">{{$attr->name}}</option>
                    @endforeach
                </select>

                <a class="btn btn-success" onclick="handleCreateViewForTable(event)">Create View</a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function handleCreateViewForTable() {
                let projectId = "{{$project->id}}";
                let processAttrs = $("#table-process-attributes").val();
                let sampleAttrs = $("#table-sample-attributes").val();
                let r = route('projects.datahq.sampleshq.create-table-view', {
                    project: projectId,
                });
                let formData = new FormData();
                formData.append("entityAttrs", sampleAttrs);
                formData.append("activityAttrs", processAttrs);
            }
        </script>
    @endpush
</div>