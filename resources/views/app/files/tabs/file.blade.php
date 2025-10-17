<x-card-container>
    @include('partials.files._file-header-controls', [
        'displayRoute' => route('projects.files.display', [$project, $file]),
        'editRoute' => route('projects.files.edit', [$project, $file]),
    ])
    {{--<form>--}}
    {{--    <div class="form-group">--}}
    {{--        <label>Tags <a href="#" class="ms-3 action-linkx">edit</a></label>--}}
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
</x-card-container>

{{--@push('scripts')--}}
{{--    <script>--}}
{{--        $(document).ready(() => {--}}
{{--            console.log('here');--}}
{{--            let tagsInput = document.querySelector('#tags');--}}
{{--            new Tagify(tagsInput);--}}
{{--        });--}}
{{--    </script>--}}
{{--@endpush--}}
