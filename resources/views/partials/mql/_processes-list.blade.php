{{--<ul class="list-unstyled ml-4">--}}
<table class="table table-sm table-borderless ml-4">
    <thead>
    <tr class="row">
        <th class="col-sm-1">Y</th>
        <th class="col-sm-1">N</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($activities as $activity)
        <tr class="row" id="{{$loop->index}}">
            @if(old('activities'))
                @php
                    $hasActivity = in_array($activity->name, old('activities'));
                    $hasNegatedActivity = in_array("!{$activity->name}", old('activities'));
                @endphp
                @if($hasActivity || $hasNegatedActivity)
                    <td class="col-sm-1">
                        <input type="checkbox" name="activities[]" value="{{$activity->name}}"
                               onclick="toggleCheckboxState(this, '{{$loop->index}}')"
                               {{$hasActivity ? 'checked' : ''}}
                               hx-post="{{route('projects.entities.mql.show', $project)}}"
                               hx-include="#mql-selection"
                               hx-target="#mql-query"
                               hx-trigger="click"
                                {{!$hasActivity ? 'disabled' : ''}}>
                    </td>
                    <td class="col-sm-1">
                        <input type="checkbox" name="activities[]" value="!{{$activity->name}}"
                               onclick="toggleCheckboxState(this, '{{$loop->index}}')"
                               {{$hasNegatedActivity ? 'checked' : ''}}
                               hx-post="{{route('projects.entities.mql.show', $project)}}"
                               hx-include="#mql-selection"
                               hx-target="#mql-query"
                               hx-trigger="click"
                                {{!$hasNegatedActivity ? 'disabled' : ''}}>
                    </td>
                @else
                    <td class="col-sm-1">
                        <input type="checkbox" name="activities[]" value="{{$activity->name}}"
                               onclick="toggleCheckboxState(this, '{{$loop->index}}')"
                               hx-post="{{route('projects.entities.mql.show', $project)}}"
                               hx-include="#mql-selection"
                               hx-target="#mql-query"
                               hx-trigger="click">
                    </td>
                    <td class="col-sm-1">
                        <input type="checkbox" name="activities[]" value="!{{$activity->name}}"
                               onclick="toggleCheckboxState(this, '{{$loop->index}}')"
                               hx-post="{{route('projects.entities.mql.show', $project)}}"
                               hx-include="#mql-selection"
                               hx-target="#mql-query"
                               hx-trigger="click">
                    </td>
                @endif
            @else
                <td class="col-sm-1">
                    <input type="checkbox" name="activities[]" value="{{$activity->name}}"
                           onclick="toggleCheckboxState(this, '{{$loop->index}}')"
                           hx-post="{{route('projects.entities.mql.show', $project)}}"
                           hx-include="#mql-selection"
                           hx-target="#mql-query"
                           hx-trigger="click">
                </td>
                <td class="col-sm-1">
                    <input type="checkbox" name="activities[]" value="!{{$activity->name}}"
                           onclick="toggleCheckboxState(this, '{{$loop->index}}')"
                           hx-post="{{route('projects.entities.mql.show', $project)}}"
                           hx-include="#mql-selection"
                           hx-target="#mql-query"
                           hx-trigger="click">
                </td>
            @endif
            <td>{{$activity->name}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        function toggleCheckboxState(checkbox, id) {
            console.log(`id = ${id}`);
            let checkboxes = $(`#${id} td input`);

            // There are two checkboxes for the In Process choices, Y(es) and N(o). When a checkbox is
            // checked, we want to disable the opposite checkbox. If a checkbox is unchecked then we want
            // to enable both checkboxes.
            let yesCheckbox = checkboxes[0];
            let noCheckbox = checkboxes[1];

            if (yesCheckbox.checked) {
                // yes checkbox has been checked so disable the no checkbox.
                noCheckbox.disabled = true;
            } else if (noCheckbox.checked) {
                // no checkbox has been checked so disable the yes checkbox.
                yesCheckbox.disabled = true;
            } else {
                // Neither checkbox is checked, so this is an uncheck event. Enable both checkboxes.
                yesCheckbox.disabled = false;
                noCheckbox.disabled = false;
            }
        }
    </script>
@endpush
{{--</ul>--}}