@include('app.projects.datasets.ce-tabs._short-overview')
<table id="activities" class="table table-hover" style="width:100%" x-data="datasetsCETabsActivities">
    <thead>
    <tr>
        <th>Name</th>
        <th>Studies</th>
        <th>Selected</th>
    </tr>
    </thead>
    <tbody>
    @foreach($project->activities as $activity)
        <tr>
            <td>
                <a href="#">{{$activity->name}}</a>
            </td>
            <td>{{$activityExperiments($activity)}}</td>
            <td>
                <div class="mb-3 form-check-inline">
                    <input type="checkbox" class="form-check-input" id="{{$activity->uuid}}"
                           {{$activityInDataset($activity) ? 'checked' : ''}}
                           onclick="updateActivitySelection({{$activity}}, this)">
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#activities').DataTable({
                pageLength: 100,
                stateSave: true,
            });
        });

        mcutil.onAlpineInit("datasetsCETabsActivities", () => {
            return {
                projectId: "{{$project->id}}",
                datasetId: "{{$dataset->id}}",
                apiToken: "{{$user->api_token}}",
                route: "{{route('api.projects.datasets.activities.selection', [$dataset])}}",

                updateActivitySelection(activity, checkbox) {
                    if (checkbox.checked) {
                        this.addActivity(activity);
                    } else {
                        this.removeActivity(activity);
                    }
                },

                addActivity(activity) {
                    axios.put(`${this.route}?api_token=${this.apiToken}`, {
                        project_id: this.projectId,
                        activity_id: activity.id,
                    });
                },

                removeActivity(activity) {
                    axios.put(`${this.route}?api_token=${this.apiToken}`, {
                        project_id: this.projectId,
                        activity_id: activity.id,
                    });
                }
            }
        });
    </script>
@endpush
