<span class="fs-10 grey-5">Directory: {{$file->fullPath()}}</span>
<x-show-standard-details :item="$file">
    <span class="ml-3 fs-10 grey-5">Mediatype: {{$file->mime_type}}</span>
    <span class="ml-3 fs-10 grey-5">Size: {{$file->toHumanBytes()}}</span>
</x-show-standard-details>
<hr>
<br>
@include('partials.files._display-file', [ 'displayRoute' => route('projects.files.display', [$project, $file]) ])
