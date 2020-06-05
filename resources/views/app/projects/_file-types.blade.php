<div class="mt-2">
    <h5>File Types ({{sizeof($fileDescriptionTypes)}}):</h5>
    <ul>
        @foreach($fileDescriptionTypes as $type => $count)
            @if($loop->iteration < 12)
                <li>{{$type}} ({{$count}})</li>
            @else
                <li class="hidden-file-type" hidden>{{$type}} ({{$count}})</li>
            @endif
        @endforeach
        @if(sizeof($fileDescriptionTypes) >= 12)
            <a href="#" onclick="toggleFileTypesShown()" id="hidden-file-types-text">
                See {{sizeof($fileDescriptionTypes)-11}} more file types...
            </a>
        @endif
    </ul>
</div>

@push('scripts')
    <script>
        let fileDescriptionTypesCount = {{sizeof($fileDescriptionTypes)-11}};

        function toggleFileTypesShown() {
            $('.hidden-file-type').attr('hidden', (_, attr) => !attr);
            let text = $('#hidden-file-type').text();
            if (text.startsWith("See")) {
                $('#hidden-file-types-text').text(`Hide ${fileDescriptionTypesCount} more file-types...`);
            } else {
                $('#hidden-file-types-text').text(`See ${fileDescriptionTypesCount} more file types...`);
            }
        }
    </script>
@endpush