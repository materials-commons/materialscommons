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
            $(`#${id} td input`).each(function (i, e) {
                console.log("e", e)
            })
        }
    </script>
@endpush
{{--</ul>--}}