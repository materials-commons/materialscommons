<form method="post" action="{{$createProjectRoute}}" id="project-create">
    @csrf
    <div class="form-group">
        <label for="name">Name</label>
        <input class="form-control" id="name" name="name" type="text" value="" placeholder="Name...">
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" type="text" value=""
                  placeholder="Description..."></textarea>
    </div>
    <div class="float-right">
        <a href="{{$cancelRoute}}" class="action-link danger mr-3">
            Cancel
        </a>

        <a class="action-link @isset($createAndNext) mr-3 @endisset"
           href="#" onclick="document.getElementById('project-create').submit()">
            Create
        </a>

        @isset($createAndNext)
            <a class="action-link" href="#" onclick="createAndNext()">
                {{$createAndNext}}
            </a>
        @endisset
    </div>
</form>

@isset($createAndNext)
    @push('scripts')
        <script>
            function createAndNext() {
                let route = "{{$createAndNextRoute}}";
                $("#project-create").attr('action', route);
                document.getElementById('project-create').submit();
            }
        </script>
    @endpush
@endisset