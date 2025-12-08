<div class="col-lg-10 float-end">
    <label>Select Data For:</label>
    <select wire:model.live="selectedData" class="custom-select col-4 font-weight-bolder" title="Select Data For">
        <option value="project" @selected($selectedData == "project")>Project</option>
        @foreach($experiments as $experiment)
            <option value="e-{{$experiment->id}}" @selected($selectedData == "e-{$experiment->id}")>
                Study: {{$experiment->name}}</option>
        @endforeach
    </select>
    <label class="ms-4">Show:</label>
    <select wire:model.live="selectedExplorer" class="custom-select col-4 font-weight-bolder" title="View">
        <option value="overview" @selected($selectedExplorer == "overview")>Overview</option>
        <option value="samples" @selected($selectedExplorer == "samples")>Samples Explorer</option>
        <option value="computations" @selected($selectedExplorer == "computations")>Computations
            Explorer
        </option>
        <option value="processes" @selected($selectedExplorer == "processes")>Processes Explorer
        </option>
    </select>
</div>
