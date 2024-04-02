<span class="fs-10 grey-5">Path: {{$file->fullPath()}}</span>
<a href="#" onclick="mcutil.copyToClipboard('{{$file->fullPath()}}')" class="ml-2">
    <i class="fa fas fa-clone"></i>
</a>
<x-show-standard-details :item="$file">
    <span class="ml-3 fs-10 grey-5">Mediatype: {{$file->mime_type}}</span>
    <span class="ml-3 fs-10 grey-5">Size: {{$file->toHumanBytes()}}</span>
</x-show-standard-details>
{{--<form>--}}
{{--    <div class="form-group">--}}
{{--        <label>Tags <a href="#" class="ml-3 action-linkx">edit</a></label>--}}
{{--        <ul class="list-inline">--}}
{{--            <li class="list-inline-item mt-1">--}}
{{--                <a class="badge badge-success fs-11 td-none">--}}
{{--                    Hello--}}
{{--                </a>--}}
{{--            </li>--}}
{{--        </ul>--}}
{{--    </div>--}}

{{--    <div class="form-group">--}}
{{--        <label for="tags">Tags</label>--}}
{{--        <input class="form-control" id="tags" name="tags" value="">--}}
{{--    </div>--}}
{{--</form>--}}
<hr>
<br>
@include('partials.files._display-file', [ 'displayRoute' => route('projects.files.display', [$project, $file]) ])

{{--@push('scripts')--}}
{{--    <script>--}}
{{--        $(document).ready(() => {--}}
{{--            console.log('here');--}}
{{--            let tagsInput = document.querySelector('#tags');--}}
{{--            new Tagify(tagsInput);--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}
