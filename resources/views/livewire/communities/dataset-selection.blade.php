<div>
    <h3 class="text-center">Community: {{ $community->name }}</h3>

    <br/>

    <table id="datasets" class="table table-hover" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Selected</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datasets as $dataset)
            <tr wire:key="community-dataset-selection-{{ $dataset->id }}">
                <td>
                    <a href="{{ route('public.datasets.show', [$dataset]) }}">
                        <i class="fa-fw fas me-2 fa-file"></i>{{ $dataset->name }}
                    </a>
                </td>
                <td>{{ $dataset->summary }}</td>
                <td>
                    <div class="mb-3 form-check-inline">
                        <input type="checkbox"
                               class="form-check-input"
                               id="{{ $dataset->uuid }}"
                               wire:click="toggleDataset({{ $dataset->id }})"
                            @checked($dataset->communities->contains($community->id))>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="float-end">
        <a href="{{ route('communities.edit', [$community]) }}" class="action-link">Done</a>
    </div>

    @push('scripts')
        <script>
            $(document).ready(() => {
                $('#datasets').DataTable({
                    pageLength: 100,
                    stateSave: true,
                });
            });
        </script>
    @endpush
</div>
