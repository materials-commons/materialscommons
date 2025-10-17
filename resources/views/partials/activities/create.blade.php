<form method="post" action="{{$createActivityRoute}}" id="create-activity">
    @csrf
    <div class="mb-3">
        <label for="name">Name</label>
        <input class="form-control" id="name" name="name" type="text" value="{{old('name')}}"
               placeholder="Name...">
    </div>
    <div class="mb-3">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" type="text"
                  placeholder="Description...">{{old('description')}}</textarea>
    </div>
    <div class="float-right">
        <a href="{{$cancelRoute}}" class="action-link danger me-3">
            Cancel
        </a>

        <a class="action-link" href="#" onclick="document.getElementById('create-activity').submit()">
            Create
        </a>
    </div>
</form>