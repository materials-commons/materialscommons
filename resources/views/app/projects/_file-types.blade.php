<div class="mt-2">
    <h5>File Types ({{sizeof($fileTypes)}}):</h5>
    <ul>
        @foreach($fileDescriptionTypes as $type => $count)
            @if($loop->iteration < 3)
                <li>{{$type}} ({{$count}})</li>
            @else
                <li class="hidden-file-type" hidden>{{$type}} ({{$count}})</li>
            @endif
        @endforeach
        @if(sizeof($fileTypes) > 3)
            <a href="#" onclick="toggleFileTypesShown()" id="hidden-file-types-text">See {{sizeof($fileTypes)-3}} more
                file types...</a>
        @endif
    </ul>
</div>

@push('scripts')
    <script>
        let fileTypesCount = {{sizeof($fileTypes)-3}};

        function toggleFileTypesShown() {
            $('.hidden-file-type').attr('hidden', (_, attr) => !attr);
            let text = $('#hidden-file-type').text();
            if (text.startsWith("See")) {
                $('#hidden-file-types-text').text(`Hide ${fileTypesCount} more file-types...`);
            } else {
                $('#hidden-file-types-text').text(`See ${fileTypesCount} more file types...`);
            }
        }
    </script>
@endpush