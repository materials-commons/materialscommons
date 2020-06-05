<div class="mt-2">
    <h5>Process Types ({{sizeof($activitiesGroup)}}):</h5>
    <ul id="process-types">
        @foreach($activitiesGroup as $ag)
            @if($loop->iteration < 12)
                <li>{{$ag->name}} ({{$ag->count}})</li>
            @else
                <li class="hidden-process" hidden>{{$ag->name}} ({{$ag->count}})</li>
            @endif
        @endforeach
        @if(sizeof($activitiesGroup) >= 12)
            <a href="#" onclick="toggleProcessesShown()" id="hidden-process-text">See {{sizeof($activitiesGroup)-11}}
                more processes...</a>
        @endif
    </ul>
</div>

@push('scripts')
    <script>
        let activitiesCount = {{sizeof($activitiesGroup)-11}};

        function toggleProcessesShown() {
            $('.hidden-process').attr('hidden', (_, attr) => !attr);
            let text = $('#hidden-process-text').text();
            if (text.startsWith("See")) {
                $('#hidden-process-text').text(`Hide ${activitiesCount} more processes...`);
            } else {
                $('#hidden-process-text').text(`See ${activitiesCount} more processes...`);
            }
        }
    </script>
@endpush