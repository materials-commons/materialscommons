<form method="post" action="{{$createProjectRoute}}" id="project-create">
    @csrf
    <div class="form-group">
        <label for="name">Name</label>
        <input class="form-control" id="name" name="name" type="text" value="{{old('name')}}"
               placeholder="Name...">
    </div>
    <div class="form-group">
        <label for="summary">Summary</label>
        <input class="form-control" id="summary" name="summary" type="text" value="{{old('summary')}}"
               placeholder="Summary...">
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" type="text"
                  placeholder="Description...">{{old('description')}}</textarea>
    </div>
    <div class="float-end" x-data="_createProject">
        <a href="{{$cancelRoute}}" class="action-link danger me-3">
            Cancel
        </a>

        <a class="action-link @isset($createAndNext) me-3 @endisset"
           href="#" onclick="document.getElementById('project-create').submit()">
            Create
        </a>

        @isset($createAndNext)
            <a class="action-link" href="#" @click.prevent="createAndNext()">
                {{$createAndNext}}
            </a>
        @endisset
    </div>
</form>

@isset($createAndNext)
    @push('scripts')
        <script>
            mcutil.onAlpineInit("_createProject", () => {
                return {
                    createAndNext() {
                        let route = "{!! $createAndNextRoute !!}";
                        $("#project-create").attr('action', route);
                        document.getElementById('project-create').submit();
                    }
                }
            })
        </script>
    @endpush
@endisset