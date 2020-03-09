@component('components.card')
    @slot('header')
        Processes
    @endslot

    @slot('body')
        <table id="activities" class="table table-hover" style="width:100%">
            <thead>
            <tr>
                <th>Name</th>
                <th>Experiments</th>
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
                        <div class="form-group form-check-inline">
                            <input type="checkbox" class="form-check-input" id="{{$activity->uuid}}"
                                   {{$activityInDataset($activity) ? 'checked' : ''}}
                                   onclick="updateActivitySelection({{$activity}}, this)">
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endslot
@endcomponent

@push('scripts')
    <script>
        let projectId = "{{$project->id}}";
        let datasetId = "{{$dataset->id}}";
        let apiToken = "{{$user->api_token}}";
        let route = "{{route('api.projects.datasets.activities.selection', [$dataset])}}";

        $(document).ready(() => {
            $('#activities').DataTable({
                stateSave: true,
            });
        });

        function updateActivitySelection(activity, checkbox) {
            if (checkbox.checked) {
                addActivity(activity);
            } else {
                removeActivity(activity);
            }
        }

        function addActivity(activity) {
            axios.put(`${route}?api_token=${apiToken}`, {
                project_id: projectId,
                activity_id: activity.id,
            });
        }

        function removeActivity(activity) {
            axios.put(`${route}?api_token=${apiToken}`, {
                project_id: projectId,
                activity_id: activity.id,
            });
        }
    </script>
@endpush
