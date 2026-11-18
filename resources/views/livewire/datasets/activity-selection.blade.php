<div>
    @include('app.projects.datasets.ce-tabs._short-overview')

    <table id="activities" class="table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Studies</th>
            <th>Selected</th>
        </tr>
        </thead>
        <tbody>
        @foreach($activities as $activity)
            <tr wire:key="dataset-activity-selection-{{ $activity->id }}">
                <td>
                    <a href="#">{{ $activity->name }}</a>
                </td>
                <td>{{ $this->activityExperiments($activity) }}</td>
                <td>
                    <div class="mb-3 form-check-inline">
                        <input type="checkbox"
                               class="form-check-input"
                               id="{{ $activity->uuid }}"
                               wire:click="toggleActivity({{ $activity->id }})"
                            @checked($this->activityInDataset($activity))>
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
        </script>
    @endpush
</div>
