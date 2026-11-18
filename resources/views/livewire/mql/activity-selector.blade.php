<div>
    <div class="row mb-2">
        @if($category == "computational")
            <span class="me-2 ms-3">In Activity:</span>
        @else
            <span class="me-2 ms-3">In Process:</span>
        @endif
    </div>

    <select id="activities" wire:model.live="selectedActivity">
        <option value=""></option>
        @foreach($activityNames ?? [] as $activityName)
            <option value="{{ $activityName }}">{{ $activityName }}</option>
        @endforeach
    </select>

    @push('scripts')
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('mql-activity-entities-matched', (event) => {
                    let entities = event.entities ?? [];
                    let api = $('#entities-with-used-activities').DataTable();

                    if (entities.length === 0) {
                        api.search('').columns().search('').draw();
                        return;
                    }

                    let searchStr = "";
                    for (let i = 0; i < entities.length; i++) {
                        let entity = entities[i];
                        if (i === 0) {
                            searchStr = `^${entity}$`;
                        } else {
                            searchStr = searchStr + `|^${entity}$`;
                        }
                    }

                    api.column(0).search(searchStr, true, false).draw();
                });
            });
        </script>
    @endpush
</div>
