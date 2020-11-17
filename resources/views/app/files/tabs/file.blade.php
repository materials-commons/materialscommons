<x-show-standard-details :item="$file">
    <span class="ml-3 fs-10 grey-5">Mediatype: {{$file->mime_type}}</span>
    <span class="ml-3 fs-10 grey-5">Size: {{$file->toHumanBytes()}}</span>
    @if ($file->previousVersions->count() > 0)
        <a class="ml-3 fs-10 grey-5" href="#">Versions: {{$file->previousVersions->count()}}</a>
    @else
        <span class="ml-3 fs-10 grey-5">Versions: 0</span>
    @endif
</x-show-standard-details>
<hr>
<br>
@include('partials.files._display-file', [ 'displayRoute' => route('projects.files.display', [$project, $file]) ])
